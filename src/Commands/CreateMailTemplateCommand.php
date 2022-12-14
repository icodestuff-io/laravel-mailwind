<?php

namespace Icodestuff\Mailwind\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateMailTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwind:create {name : The name of the template} {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Mailwind blade template.';

    public function handle(Filesystem $filesystem): int
    {
        $newFileName = $this->camel2dashed($this->argument('name')).'.blade.php';
        $newFilePath = resource_path('views/vendor/mailwind/templates').'/'.$newFileName;

        if (! $this->option('force') && $filesystem->exists($newFilePath)) {
            $this->warn("The template: $newFilePath already exists.");

            return self::FAILURE;
        }

        try {
            $filesystem->put(
                $newFilePath,
                file_get_contents(dirname(__DIR__, 2).'/resources/views/templates/mailwind-example-template.blade.php')
            );
        } catch (\Exception $exception) {
            $this->warn($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Created the file '$newFilePath'");

        return self::SUCCESS;
    }

    private function camel2dashed($className)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));
    }
}
