<?php

namespace App\Listeners;

use samdark\sitemap\Sitemap;
use TightenCo\Jigsaw\Jigsaw;

class GenerateSitemap
{
    const EXCLUDE_PATHS = [
        '/CNAME',
        '/favicon.png',
    ];

    public function handle(Jigsaw $jigsaw)
    {
        $baseUrl = $jigsaw->getConfig('baseUrl');
        $sitemap = new Sitemap($jigsaw->getDestinationPath() . '/sitemap.xml');

        collect($jigsaw->getOutputPaths())->each(function ($path) use ($baseUrl, $sitemap) {
            if (! $this->isAsset($path) && ! in_array($path, self::EXCLUDE_PATHS)) {
                $sitemap->addItem($baseUrl . $path, time(), Sitemap::WEEKLY);
            }
        });

        $sitemap->write();
    }

    private function isAsset($path)
    {
        return str_starts_with($path, '/assets');
    }
}