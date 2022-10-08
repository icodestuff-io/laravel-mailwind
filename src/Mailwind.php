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
        $this->filesystem->ensureDirectoryExists(resource_path('views/vendor/mailwind/'));

        if (! $this->viewFactory->exists($viewName)) {
            throw new ViewException("The view: $viewName does not exist.");
        }

        $viewPath = view($viewName)->getPath();

        $cachedFileName = $regenerate ? null : $this->cacheRepository->get($viewName);

        if ($cachedFileName === null) {
            $cachedFileName = $this->generateMailwindTemplate($viewPath);
        }

        $cachedFileExists = $this->filesystem->exists(resource_path("views/vendor/mailwind/generated/$cachedFileName"));

        if ($cachedFileExists === false) {
            $cachedFileName = $this->generateMailwindTemplate($viewPath);
        }

        $view = Str::remove('.blade.php', $cachedFileName);
        $this->cacheManager->set($viewName, $cachedFileName);

        return "mailwind::generated.$view";
    }

    private function generateMailwindTemplate(string $viewPath)
    {
        $fileName = Str::random().'.blade.php';
        $cachedFilePath = resource_path("views/vendor/mailwind/generated/$fileName");

        $exitCode = $this->kernel->call('mailwind:generate', [
            '--input-html' => $viewPath,
            '--output-html' => $cachedFilePath,
        ]);

        if ($exitCode !== 0) {
            throw new CompilationFailedException("Failed to run compile command: `$viewPath`");
        }

        return $fileName;
    }
}
