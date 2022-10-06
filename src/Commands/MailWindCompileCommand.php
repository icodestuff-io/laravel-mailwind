<?php

namespace Icodestuff\MailWind\Commands;

use Icodestuff\MailWind\MailWind;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Str;

class MailWindCompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwind:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile all TailwindCSS email templates to valid email HTML.';

    public function handle(FilesystemManager $filesystemManager, MailWind $mailWind): int
    {
        $files = $filesystemManager->files(resource_path('views/mail/template'));
        foreach ($files as $file) {
            if (! Str::contains($file->getFilename(), '.blade.php')) {
                $this->warn("Invalid file extension for: {$file->getFilename()}. Skipped.");

                continue;
            }

            $viewName = 'mail.template.'.Str::remove('.blade.php', $file->getFilename());
            $res = $mailWind->compile($viewName);
            dd($res);
        }

        return self::SUCCESS;
    }
}
