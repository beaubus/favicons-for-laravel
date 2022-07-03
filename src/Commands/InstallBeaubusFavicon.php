<?php

namespace Beaubus\FaviconsForLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallBeaubusFavicon extends Command
{
    public $signature = 'beaubus:install-favicon {name}';

    public $description = 'Install BEAUBUS Favicon';

    public function handle(): int
    {
        $name = $this->argument('name');

        if (! $favicon = $this->getFavicon($name)) {
            $this->error("There is no favicon with a name $name");

            return self::FAILURE;
        }

        $this->extractFavicon($favicon);

        $this->comment('All done');

        return self::SUCCESS;
    }

    private function extractFavicon($favicon)
    {
        $tmp_folder = 'beaubus_favicon_tmp_' . now()->timestamp;

        // Download zip into tmp folder
        $contents = \Http::get($favicon['zip'])->body();
        \Storage::disk('public')->put("$tmp_folder/tmp.zip", $contents);

        // Unzip into tmp folder
        $zip = new \ZipArchive;
        $zip->open(storage_path("app/public/$tmp_folder/tmp.zip"));
        $zip->extractTo(storage_path("app/public/$tmp_folder"));
        $zip->close();

        // Copy favicon.ico and favicon.svg into public folder
        file_put_contents(public_path('favicon.svg'), $favicon['svg']);
        copy(storage_path("app/public/$tmp_folder/favicon.ico"), public_path('favicon.ico'));

        // Move from tmp into beaubus-favicons
        $file = new Filesystem();
        if ($file->moveDirectory(storage_path("app/public/$tmp_folder"), storage_path('app/public/beaubus-favicons'), true)) {
            \Storage::disk('public')->delete([
                'beaubus-favicons/README.md',
                'beaubus-favicons/tmp.zip'
            ]);
        }

        sleep(2); // wait for directory is moved @todo: refactor

        // Replace paths in browserconfig
        $browserconfig_path = 'beaubus-favicons/browserconfig.xml';
        $browserconfig = \Storage::disk('public')->get($browserconfig_path);
        $browserconfig = preg_replace('/src=\"\//', 'src="/storage/beaubus-favicons/', $browserconfig);
        \Storage::disk('public')->put($browserconfig_path, $browserconfig);

        // Replace paths in webmanifest
        $webmanifest_path = 'beaubus-favicons/site.webmanifest';
        $webmanifest = \Storage::disk('public')->get($webmanifest_path);
        $webmanifest = preg_replace('/\"src\": \"\//', '"src": "/storage/beaubus-favicons/', $webmanifest);
        \Storage::disk('public')->put($webmanifest_path, $webmanifest);
    }

    private function getFavicon($name): array|null
    {
        return \Http::get('https://favicons.beaubus.com/favicons.json')
            ->collect()->filter(function ($favicon) use ($name) {
            return \Str::lower($favicon['name']) == \Str::lower($name);
        })->first();
    }
}