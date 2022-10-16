<?php

namespace CodeLabX\XtendLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class XtendLaravelSetupCommand extends Command
{
    public $signature = 'xtend-laravel:setup';

    public $description = 'Setup the XtendLaravel package structure with your laravel application';

    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->createXtendStructure();

        $this->info('All done');

        return self::SUCCESS;
    }

    protected function createXtendStructure(): void
    {
        $xtendPackageDir = config('xtend-laravel.directory', 'xtend');

        if (! $this->filesystem->isDirectory($path = $this->laravel->basePath($xtendPackageDir))) {
            $this->filesystem->makeDirectory($path.'/Core', 0755, true);
            $this->filesystem->put($path.'/Core/.gitkeep', '');
            $this->comment('Created Xtend directory structure');
        } else {
            $this->comment('Xtend directory structure already exists');
        }
    }
}
