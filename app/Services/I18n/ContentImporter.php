<?php

namespace App\Services\I18n;

use App\Models\GlobalSettings;
use App\Models\SeoEntity;
use Illuminate\Support\Facades\DB;

class ContentImporter
{
    public const STATS = ['written', 'skipped_existing', 'skipped_source_changed', 'skipped_no_translation'];

    /** @var array<string, array<string,int>> */
    public array $stats = [];

    /** @var string[] human readable descriptions of entries whose source changed */
    public array $sourceChanged = [];

    public function __construct(
        private string $source,
        private string $target,
        private bool $force = false,
        private bool $dryRun = false,
    ) {
        foreach (['model', 'meta', 'setting'] as $kind) {
            $this->stats[$kind] = array_fill_keys(self::STATS, 0);
        }
        $this->stats['seo non-text'] = ['copied' => 0];
    }

    public function import(array $entries): void
    {
        DB::transaction(function () use ($entries) {
            foreach ($entries as $entry) {
                $kind = $entry['kind'] ?? null;
                if (!isset($this->stats[$kind]) || $kind === 'seo non-text') {
                    continue;
                }
                $translation = $entry['translation'] ?? null;
                if ($translation === null || $translation === '' || $translation === []) {
                    $this->stats[$kind]['skipped_no_translation']++;
                    continue;
                }
                $translation = LinkRewriter::rewrite($translation, $this->source, $this->target);

                match ($kind) {
                    'model' => $this->importModel($entry, $translation),
                    'meta' => $this->importMeta($entry, $translation),
                    'setting' => $this->importSetting($entry, $translation),
                };
            }

            $this->copySeoNonText();
        });
    }

    private function same(mixed $a, mixed $b): bool
    {
        return json_encode($a) === json_encode($b);
    }

    private function changed(string $kind, string $label): void
    {
        $this->stats[$kind]['skipped_source_changed']++;
        $this->sourceChanged[] = $label;
    }

    private function importModel(array $entry, mixed $translation): void
    {
        $class = $entry['model'] ?? '';
        $field = $entry['field'] ?? '';
        $label = "model {$class}#" . ($entry['id'] ?? '?') . ".{$field}";

        if (!is_string($translation) || !isset(ContentCatalog::MODEL_FIELDS[$class])
            || !in_array($field, ContentCatalog::MODEL_FIELDS[$class], true)) {
            $this->stats['model']['skipped_no_translation']++;
            return;
        }

        $model = ContentCatalog::newModelQuery($class)->find($entry['id'] ?? 0);
        if (!$model || ContentCatalog::translation($model, $field, $this->source) !== ($entry['value'] ?? null)) {
            $this->changed('model', $label);
            return;
        }
        if (!$this->force && ContentCatalog::translation($model, $field, $this->target) !== null) {
            $this->stats['model']['skipped_existing']++;
            return;
        }

        if (!$this->dryRun) {
            $model->setTranslation($field, $this->target, $translation)->saveQuietly();
        }
        $this->stats['model']['written']++;
    }

    private function importMeta(array $entry, mixed $translation): void
    {
        $table = $entry['table'] ?? '';
        $key = (string) ($entry['key'] ?? '');
        $label = "meta {$table} " . ($entry['owner_type'] ?? '?') . '#' . ($entry['owner_id'] ?? '?') . ".{$key}";

        if (!in_array($table, ContentCatalog::META_TABLES, true) || $key === '') {
            $this->stats['meta']['skipped_no_translation']++;
            return;
        }

        $type = $entry['type'] ?? null;
        $json = in_array($type, ContentCatalog::META_JSON_TYPES, true);
        if ($json !== is_array($translation)) {
            $this->stats['meta']['skipped_no_translation']++;
            return;
        }

        $row = fn (string $locale) => DB::table($table)
            ->where('owner_type', $entry['owner_type'] ?? '')
            ->where('owner_id', $entry['owner_id'] ?? 0)
            ->where('key', $key . '_' . $locale)
            ->first();

        $sourceRow = $row($this->source);
        $current = $sourceRow ? ContentCatalog::decodeStored($sourceRow->value, $json) : null;
        if ($current === null || !$this->same($current, $entry['value'] ?? null)) {
            $this->changed('meta', $label);
            return;
        }

        $targetRow = $row($this->target);
        if ($targetRow && !$this->force && !ContentCatalog::isEmptyStored($targetRow->value)) {
            $this->stats['meta']['skipped_existing']++;
            return;
        }

        if (!$this->dryRun) {
            $stored = ContentCatalog::encodeStored($translation);
            if ($targetRow) {
                $update = ['value' => $stored, 'updated_at' => now()];
                if ($sourceRow->type !== null) {
                    $update['type'] = $sourceRow->type;
                }
                DB::table($table)->where('id', $targetRow->id)->update($update);
            } else {
                $insert = [
                    'key' => $key . '_' . $this->target,
                    'value' => $stored,
                    'status' => $sourceRow->status,
                    'owner_type' => $entry['owner_type'],
                    'owner_id' => $entry['owner_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if ($sourceRow->type !== null) {
                    $insert['type'] = $sourceRow->type;
                }
                DB::table($table)->insert($insert);
            }
        }
        $this->stats['meta']['written']++;
    }

    private function importSetting(array $entry, mixed $translation): void
    {
        $key = (string) ($entry['key'] ?? '');
        $label = "setting {$key}";

        $sourceRow = DB::table('global_settings')->where('key', $key . '_' . $this->source)->first();
        $decoded = $sourceRow && $sourceRow->value !== null ? json_decode($sourceRow->value, true) : null;
        $current = is_array($decoded) ? $decoded : ($sourceRow->value ?? null);
        if ($key === '' || $current === null || !$this->same($current, $entry['value'] ?? null)) {
            $this->changed('setting', $label);
            return;
        }

        $targetRow = DB::table('global_settings')->where('key', $key . '_' . $this->target)->first();
        if ($targetRow && !$this->force && !ContentCatalog::isEmptyStored($targetRow->value)) {
            $this->stats['setting']['skipped_existing']++;
            return;
        }

        if (!$this->dryRun) {
            GlobalSettings::set($key . '_' . $this->target, $translation);
        }
        $this->stats['setting']['written']++;
    }

    /** is_active and og_image are translatable but not text: copy source -> target when target is unset. */
    private function copySeoNonText(): void
    {
        foreach (SeoEntity::query()->orderBy('id')->cursor() as $seo) {
            $dirty = false;

            $active = $seo->getTranslations('is_active');
            if (array_key_exists($this->source, $active) && !array_key_exists($this->target, $active)) {
                $seo->setTranslation('is_active', $this->target, $active[$this->source]);
                $this->stats['seo non-text']['copied']++;
                $dirty = true;
            }

            $og = $seo->getTranslations('og_image');
            $sourceOg = $og[$this->source] ?? null;
            $targetOg = $og[$this->target] ?? null;
            if (!empty($sourceOg) && empty($targetOg)) {
                $seo->setTranslation('og_image', $this->target, $sourceOg);
                $this->stats['seo non-text']['copied']++;
                $dirty = true;
            }

            if ($dirty && !$this->dryRun) {
                $seo->saveQuietly();
            }
        }
    }
}
