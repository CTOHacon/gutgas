<?php

namespace App\Services\I18n;

use Illuminate\Support\Facades\DB;

class ContentExporter
{
    public function export(string $source): array
    {
        return [
            'source' => $source,
            'exported_at' => now()->toIso8601String(),
            'entries' => array_merge(
                $this->modelEntries($source),
                $this->metaEntries($source),
                $this->settingEntries($source)
            ),
        ];
    }

    private function modelEntries(string $source): array
    {
        $entries = [];
        foreach (ContentCatalog::MODEL_FIELDS as $class => $fields) {
            foreach (ContentCatalog::newModelQuery($class)->cursor() as $model) {
                foreach ($fields as $field) {
                    $value = ContentCatalog::translation($model, $field, $source);
                    if ($value === null) {
                        continue;
                    }
                    $entries[] = [
                        'kind' => 'model',
                        'model' => $class,
                        'id' => $model->getKey(),
                        'field' => $field,
                        'value' => $value,
                    ];
                }
            }
        }

        return $entries;
    }

    private function metaEntries(string $source): array
    {
        $suffix = '_' . $source;
        $entries = [];
        foreach (ContentCatalog::META_TABLES as $table) {
            $rows = DB::table($table)->orderBy('owner_type')->orderBy('owner_id')->orderBy('key')->get();
            foreach ($rows as $row) {
                if (!str_ends_with($row->key, $suffix) || strlen($row->key) === strlen($suffix)) {
                    continue;
                }
                if (!in_array($row->type, ContentCatalog::META_TEXT_TYPES, true)) {
                    continue;
                }
                $value = ContentCatalog::decodeStored(
                    $row->value,
                    in_array($row->type, ContentCatalog::META_JSON_TYPES, true)
                );
                if ($value === null) {
                    continue;
                }
                $entries[] = [
                    'kind' => 'meta',
                    'table' => $table,
                    'owner_type' => $row->owner_type,
                    'owner_id' => (int) $row->owner_id,
                    'key' => substr($row->key, 0, -strlen($suffix)),
                    'type' => $row->type,
                    'value' => $value,
                ];
            }
        }

        return $entries;
    }

    private function settingEntries(string $source): array
    {
        $suffix = '_' . $source;
        $entries = [];
        foreach (DB::table('global_settings')->orderBy('key')->get() as $row) {
            if (!str_ends_with($row->key, $suffix) || strlen($row->key) === strlen($suffix)) {
                continue;
            }
            if ($row->value === null || trim($row->value) === '') {
                continue;
            }
            $decoded = json_decode($row->value, true);
            $value = is_array($decoded) ? $decoded : $row->value;
            if ($value === [] || $value === '') {
                continue;
            }
            $entries[] = [
                'kind' => 'setting',
                'key' => substr($row->key, 0, -strlen($suffix)),
                'value' => $value,
            ];
        }

        return $entries;
    }
}
