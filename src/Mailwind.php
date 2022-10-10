<?php

namespace Icodestuff\Mailwind;

use Icodestuff\Mailwind\Exceptions\CompilationFailedException;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\ViewException;

class Mailwind
{
    public function __construct(
        protected Filesystem $filesystem,
        protected ViewFactory $viewFactory,
        protected CacheManager $cacheManager,
        protected CacheRepository $cacheRepository,
        protected Kernel $kernel
    ) {
    }

    /**
     * @param  string  $viewName
     * @return string
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function compile(string $viewName, bool $regenerate = false): string
    {
        // @todo add https://github.com/soundasleep/html2text
        // @todo add support for svg to base64
        $this->filesystem->ensureDirectoryExists(resource_path('views/vendor/mailwind/'));

        if (! $this->viewFactory->exists($viewName)) {
            throw new ViewException("The view: $viewName does not exist.");
        }

        $viewPath = view($viewName)->getPath();

        $cache = $regenerate ? null : $this->cacheRepository->get($viewName);

        // Generate new cache
        if (!is_array($cache)) {
            $cache = [
                'file' => $this->generateMailwindTemplate($viewPath),
                'hash' => md5_file($viewPath)
            ];
        }

        $cachedFileName = $cache['file'] ?? $this->generateMailwindTemplate($viewPath);
        $hash = $cache['hash'] ?? md5_file($viewPath);

        $cachedFileExists = $this->filesystem->exists(resource_path("views/vendor/mailwind/generated/$cachedFileName"));

        if ($cachedFileExists === false) {
            $cachedFileName = $this->generateMailwindTemplate($viewPath);
        }

        // Contents of file changed,
        if ($hash !== md5_file($viewPath)) {
            $hash = md5_file($viewPath);
            $cachedFileName = $this->generateMailwindTemplate($viewPath);
        }

        $view = Str::remove('.blade.php', $cachedFileName);

        $this->cacheManager->set($viewName, [
            'file' => $cachedFileName,
            'hash' => $hash
        ]);

        return "mailwind::generated.$view";
    }

    private function generateMailwindTemplate(string $viewPath)
    {
        $fileName = Str::random().'.blade.php';
        $cachedFilePath = resource_path("views/vendor/mailwind/generated/$fileName");

        $exitCode = $this->kernel->call('mailwind:compile', [
            '--input-html' => $viewPath,
            '--output-html' => $cachedFilePath,
        ]);


        if ($exitCode !== 0) {
            $output = $this->kernel->output();
            throw new CompilationFailedException("Failed to run compile command: `$output`");
        }

        return $fileName;
    }
}
