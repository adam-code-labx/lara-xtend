<?php

namespace CodeLabX\XtendLaravel\Commands;

use CodeLabX\XtendLaravel\Facades\XtendLaravel;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

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
        $this->initialSetup();
        $this->createXtendStructure();

        $this->components->info('All done');

        return self::SUCCESS;
    }

    protected function initialSetup(): void
    {
        if (Schema::hasTable('xtend_packages')) {
            $this->components->warn('Skipping initial setup, xtend_packages table already exists');
            return;
        }

        $this->components->info('Initial setup');

        $this->components->info('Running migrations...');
        $this->call('migrate');

        $this->setupPackages();

        $this->call('vendor:publish', ['--tag' => 'xtend-laravel-config']);
        $this->starGitHubRepo();
    }

    protected function setupPackages(): void
    {
        $this->components->info('Installing packages...');
        XtendLaravel::manager()->installPackages();
    }

    protected function createXtendStructure(): void
    {
        $xtendPackageDir = config('xtend-laravel.directory', 'xtend');
        if (!$exists = $this->filesystem->isDirectory($path = $this->laravel->basePath($xtendPackageDir))) {
            $this->filesystem->makeDirectory($path . '/Extensions', 0755, true);
        }

        $this->components->info(
            $exists
                ? 'Xtend directory structure already exists'
                : 'Created Xtend directory structure located at ' . $path
        );
    }

    protected function starGitHubRepo(): void
    {
        if ($this->components->confirm('Would you like to star our repo on GitHub?')) {
            $repoUrl = 'https://github.com/adam-code-labx/xtend-laravel';

            if (PHP_OS_FAMILY == 'Darwin') {
                exec("open {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec("start {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec("xdg-open {$repoUrl}");
            }
        }
    }
}
