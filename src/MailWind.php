<?php

namespace Icodestuff\MailWind;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Factory as ViewFactory;

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
            // @todo: Change to MailwindNotFoundException
            throw new \Exception("The view:  $viewName does not exist.");
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

        $command = './mw --input-html '.$viewPath.' --output-html '.$cachedFilePath;

        $output = shell_exec($command);
        if ($output === false) {
            // todo: MailwindFileNotCreated
            throw new \Exception('failed to create file');
        }

        return $fileName;
    }
}
