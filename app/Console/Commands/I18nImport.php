<?php

namespace App\Console\Commands;

use App\Services\I18n\ContentImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class I18nImport extends Command
{
    protected $signature = 'i18n:import {file : Export file with an added "translation" key per entry} {--target=ka : Target locale} {--force : Overwrite existing target values} {--dry-run : Write nothing}';

    protected $description = 'Import translated content (output of i18n:export + "translation" keys) as the target locale';

    public function handle(): int
    {
        $file = (string) $this->argument('file');
        if (!str_starts_with($file, '/')) {
            $file = base_path($file);
        }
        if (!File::exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $data = json_decode(File::get($file), true);
        if (!is_array($data) || !isset($data['source'], $data['entries']) || !is_array($data['entries'])) {
            $this->error('Invalid import file: expected "source" and "entries".');
            return self::FAILURE;
        }

        $source = (string) $data['source'];
        $target = (string) $this->option('target');
        if (!preg_match('/^[A-Za-z0-9-]{2,10}$/', $target) || !preg_match('/^[A-Za-z0-9-]{2,10}$/', $source)) {
            $this->error('Invalid locale code.');
            return self::FAILURE;
        }
        // Same locale = patching the source text itself; only meaningful with --force.
        if ($source === $target && !$this->option('force')) {
            $this->error('Source and target locales are the same (use --force to patch the source locale).');
            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');
        $importer = new ContentImporter($source, $target, (bool) $this->option('force'), $dry);
        $importer->import($data['entries']);

        $rows = [];
        foreach ($importer->stats as $kind => $s) {
            $rows[] = $kind === 'seo non-text'
                ? [$kind, $s['copied'] . ' copied', '', '', '']
                : [$kind, $s['written'], $s['skipped_existing'], $s['skipped_source_changed'], $s['skipped_no_translation']];
        }
        $this->table(['kind', 'written', 'skipped-existing', 'skipped-source-changed', 'skipped-no-translation'], $rows);

        if ($importer->sourceChanged) {
            $this->warn('Skipped because the source value changed since export:');
            foreach ($importer->sourceChanged as $label) {
                $this->line(' - ' . $label);
            }
        }

        $this->info($dry ? 'Dry run: nothing was written.' : "Imported into locale '{$target}'.");

        return self::SUCCESS;
    }
}
