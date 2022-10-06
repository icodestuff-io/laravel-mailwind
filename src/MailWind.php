<?php

namespace Icodestuff\MailWind;

use Icodestuff\MailWind\Exceptions\NpxFailedException;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\ViewException;

class MailWind
{
    public function __construct(
        protected Filesystem $filesystem,
        protected ViewFactory $viewFactory,
        protected CacheManager $cacheManager,
        protected CacheRepository $cacheRepository
    ) {
    }

    /**
     * @param  string  $viewName
     * @return string
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function compile(string $viewName): string
    {
        $this->filesystem->ensureDirectoryExists(resource_path('views/vendor/mailwind/'));

        if (! $this->viewFactory->exists($viewName)) {
            throw new ViewException("The view:  $viewName does not exist.");
        }

        $viewPath = view($viewName)->getPath();

        $cachedFileName = $this->cacheRepository->get($viewPath);

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

        $command = './vendor/bin/mw --input-html '.$viewPath.' --output-html '.$cachedFilePath;

        $output = shell_exec($command);
        if ($output === false) {
            throw new NpxFailedException("Failed to run the npx command: `$command`");
        }

        return $fileName;
    }
}
