<?php

namespace Icodestuff\Mailwind\Commands;

use Icodestuff\Mailwind\Mailwind;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateMailViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwind:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile all TailwindCSS email templates to valid email HTML.';

    public function handle(Filesystem $filesystem, Mailwind $mailWind): int
    {
        $cachedFiles = array_filter($filesystem->files(resource_path('views/vendor/mailwind/generated')), function ($file) {
            return ! str_contains($file, '.gitignore');
        });
        $filesystem->delete($cachedFiles);

        $files = $filesystem->files(resource_path('views/vendor/mailwind/templates'));

        foreach ($files as $file) {
            if (! Str::contains($file->getFilename(), '.blade.php')) {
                $this->warn("Invalid file extension for: {$file->getFilename()}. Skipped.");

                continue;
            }

            $viewName = 'mailwind::templates.'.Str::remove('.blade.php', $file->getFilename());

            $cachedView = $mailWind->compile($viewName, true);

            $this->info("Compiled template for {$file->getFilename()} — $cachedView");
        }

        return self::SUCCESS;
    }
}
