<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Generate an XML Sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $indexSitemap = SitemapIndex::create();

        foreach (config('app.locales') as $locale => $value) {
            SitemapGenerator::create(config('app.url') . '/' . $locale)
                ->configureCrawler(function (Crawler $crawler) {
                    $crawler->ignoreRobots();
                })
                ->shouldCrawl(function (UriInterface $url) use ($locale) {
                    return strpos($url->getPath(), '/' . $locale . '/') !== false || strpos($url->getPath(), '/' . $locale) === strlen($url->getPath()) - 3;
                })
                ->writeToFile(public_path($locale . '-sitemap.xml'));

            $indexSitemap->add($locale . '-sitemap.xml');
        }

        $indexSitemap
            ->writeToFile(public_path('sitemap.xml'));
    }
}
