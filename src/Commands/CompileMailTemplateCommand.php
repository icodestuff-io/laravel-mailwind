<?php

namespace Icodestuff\Mailwind\Commands;

use Illuminate\Console\Command;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class CompileMailTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwind:compile
        {--input-css= : The path to your custom CSS file}
        {--input-html= : The path to your input HTML file}
        {--output-css= : The path to the CSS file that will be generated}
        {--output-html= : The path to the inlined HTML file that will be generated}
        {--tailwind-config= : The path to your custom Tailwind config file}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CssToInlineStyles $cssToInlineStyles)
    {
        if ($this->option('input-html') === null) {
            $this->warn('The --input-html option is required.');

            return self::FAILURE;
        }

        if ($this->option('output-css') === null && $this->option('output-html') === null) {
            $this->warn('Either --output-css or --output-html options must be specified.');

            return self::FAILURE;
        }

        if ($this->option('output-css') &&
            $this->option('input-css') &&
            $this->option('input-css') === $this->option('output-css')
        ) {
            $this->warn('The --input-css and --output-css options cannot refer to the same file.');

            return self::FAILURE;
        }

        if ($this->option('output-html') &&
            $this->option('input-html') &&
            $this->option('input-html') === $this->option('output-html')
        ) {
            $this->warn('The --input-html and --output-html options cannot refer to the same file.');

            return self::FAILURE;
        }

        $tailwindCSSPath = base_path('node_modules/tailwindcss/lib/cli.js');

        if (! file_exists($tailwindCSSPath)) {
            $this->warn('Please install TailwindCSS in your project by running: `npm install tailwindcss`');

            return self::FAILURE;
        }

        $tailwindConfigPath = $this->option('tailwind-config') ?? base_path('tailwind.config.js');
        $inputHtmlPath = $this->option('input-html');
        $inputCSSPath = $this->option('input-css') ?? base_path('t.css');
        $outputHtmlPath = $this->option('output-html');

        touch(storage_path('app/mailwind.css'));
        $outputCSSPath = $this->option('output-css') ?? storage_path('app/mailwind.css');

        $command = "$tailwindCSSPath --config $tailwindConfigPath --input $inputCSSPath --output $outputCSSPath --content $inputHtmlPath";
        exec($command, $termOutput, $exitCode);

        if ($exitCode !== 0) {
            $this->warn('Failed to run Tailwind.');

            return self::FAILURE;
        }

        if ($outputHtmlPath) {
            $inputHtml = file_get_contents($inputHtmlPath);
            $outputCSS = file_get_contents($outputCSSPath);

            $inlinedHtml = $cssToInlineStyles->convert($inputHtml, $outputCSS);

            $inlinedHtml = htmlspecialchars_decode($inlinedHtml);
            $inlinedHtml = urldecode($inlinedHtml);
            file_put_contents($outputHtmlPath, $inlinedHtml);
        }

        return self::SUCCESS;
    }
}
