<?php

namespace Beaubus\FaviconsForLaravel\Commands;

use Illuminate\Console\Command;

class InstallBeaubusFavicon extends Command
{
    public $signature = 'beaubus:install-favicon {name}';

    public $description = 'Install BEAUBUS Favicon';

    public function handle(): int
    {
        $name = $this->argument('name');

//        — check if favicons exists
        if (!$this->checkIfFaviconExists($name)) {
            $this->error("There is no favicon with the name $name");
            return self::FAILURE;
        }

//        — unzips to tmp
//        — moves files from tmp
//        — replaces paths in files

        $this->comment('All done');

        return self::SUCCESS;
    }

    private function checkIfFaviconExists($name): bool
    {
        return false;
    }
}