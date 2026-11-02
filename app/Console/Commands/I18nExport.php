<?php

namespace App\Console\Commands;

use App\Services\I18n\ContentExporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class I18nExport extends Command
{
    protected $signature = 'i18n:export {--source=ua : Source locale} {--out= : Output file (default storage/app/i18n-export-<source>.json)}';

    protected $description = 'Export all translatable text content of a locale to a JSON file';

    public function handle(): int
    {
        $source = (string) $this->option('source');
        if (!preg_match('/^[A-Za-z0-9-]{2,10}$/', $source)) {
            $this->error('Invalid --source locale.');
            return self::FAILURE;
        }

        $out = $this->option('out') ?: storage_path("app/i18n-export-{$source}.json");
        if (!str_starts_with($out, '/')) {
            $out = base_path($out);
        }

        $data = (new ContentExporter())->export($source);

        File::ensureDirectoryExists(dirname($out));
        File::put($out, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $counts = collect($data['entries'])->countBy('kind');
        $this->info(sprintf(
            'Exported %d entries (%s) to %s',
            count($data['entries']),
            $counts->map(fn ($n, $k) => "$k: $n")->implode(', '),
            $out
        ));

        return self::SUCCESS;
    }
}
