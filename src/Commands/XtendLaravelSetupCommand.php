<?php

namespace CodeLabX\XtendLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class XtendLaravelSetupCommand extends Command
{
    public $signature = 'xtend-laravel:setup';

    public $description = 'Setup the XtendLaravel package structure with your laravel application';

    public function handle(): int
    {
        $xtendPackageDir = $this->ask('Name of the directory to extend packages from?', 'xtend');
        $vendorLang = $this->confirm('Override translations from this directory?', true);
        $vendorViews = $this->confirm('Override package views from this directory?', true);
        $vendorConfig = $this->confirm('Override package config from this directory?', true);

        if (! is_dir($xtendPackageDir = $this->laravel->basePath($xtendPackageDir))) {
            (new Filesystem)->makeDirectory($xtendPackageDir);
            $this->info("Created directory {$xtendPackageDir}");
        }

        $this->info("Using directory {$xtendPackageDir} to extend packages from");

        $this->comment('All done');

        return self::SUCCESS;
    }
}
