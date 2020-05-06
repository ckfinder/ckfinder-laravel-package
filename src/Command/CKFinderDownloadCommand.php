<?php

namespace CKSource\CKFinderBridge\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

class CKFinderDownloadCommand extends Command
{
    const LATEST_VERSION = '3.5.1.1';
    const FALLBACK_VERSION = '3.5.1';

    protected $name = 'ckfinder:download';

    protected $description = 'Downloads the CKFinder distribution package and extracts assets.';

    /**
     * Creates URL to CKFinder distribution package.
     *
     * @return string
     */
    protected function buildPackageUrl()
    {
        $packageVersion = Kernel::MAJOR_VERSION >= 5 ? self::LATEST_VERSION : self::FALLBACK_VERSION;

        return "http://download.cksource.com/CKFinder/CKFinder%20for%20PHP/$packageVersion/ckfinder_php_$packageVersion.zip";
    }

    /**
     * Handles command execution.
     */
    public function handle()
    {
        $targetPublicPath = realpath(__DIR__ . '/../../public/');

        if (!is_writable($targetPublicPath)) {
            $this->error('The target public directory is not writable (used path: ' . $targetPublicPath . ').');

            return;
        }

        $targetConnectorPath = realpath(__DIR__ . '/../../_connector');

        if (!is_writable($targetConnectorPath)) {
            $this->error('The the connector directory is not writable (used path: ' . $targetConnectorPath . ').');

            return;
        }

        if (file_exists($targetPublicPath.'/ckfinder/ckfinder.js')) {
            $questionText =
                'It looks like the CKFinder distribution package has already been installed. ' .
                "This command will overwrite the existing files.\nDo you want to proceed? [y/n]: ";

            if (!$this->confirm($questionText)) {
                return;
            }
        }

        /** @var \Symfony\Component\Console\Helper\ProgressBar $progressBar */
        $progressBar = null;

        $maxBytes = 0;
        $ctx = stream_context_create([], [
            'notification' =>
                function ($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $bytesMax) use (&$maxBytes, &$progressBar) {
                    switch ($notificationCode) {
                        case STREAM_NOTIFY_FILE_SIZE_IS:
                            $maxBytes = $bytesMax;
                            $progressBar = $this->output->createProgressBar($bytesMax);
                            break;
                        case STREAM_NOTIFY_PROGRESS:
                            $progressBar->setProgress($bytesTransferred);
                            break;
                    }
                }
        ]);

        $this->info('Downlading the CKFinder 3 distribution package.');

        $zipContents = @file_get_contents($this->buildPackageUrl(), false, $ctx);

        if ($zipContents === false) {
            $this->error('Could not download the distribution package of CKFinder.');

            return;
        }

        if ($progressBar) {
            $progressBar->finish();
        }

        $this->line("\n" . 'Extracting CKFinder to the ' . $targetPublicPath . ' directory.');

        $tempZipFile = tempnam(sys_get_temp_dir(), 'tmp');
        file_put_contents($tempZipFile, $zipContents);
        $zip = new \ZipArchive();
        $zip->open($tempZipFile);

        $zipEntries = [];

        // These files won't be overwritten if already exists
        $filesToKeep = [
            'ckfinder/config.js',
            'ckfinder/ckfinder.html'
        ];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            if (in_array($entry, $filesToKeep) && file_exists($targetPublicPath . '/' . $entry)) {
                continue;
            }

            $zipEntries[] = $entry;
        }

        $zip->extractTo($targetPublicPath, $zipEntries);

        $fs = new Filesystem();

        $this->line('Moving the CKFinder connector to the ' . $targetConnectorPath . ' directory.');
        $fs->moveDirectory(
            $targetPublicPath . '/ckfinder/core/connector/php/vendor/cksource/ckfinder/src/CKSource/CKFinder',
            $targetConnectorPath,
            true
        );

        $this->line('Cleaning up.');

        $fs->delete([
            $tempZipFile,
            $targetPublicPath . '/ckfinder/config.php',
            $targetPublicPath . '/ckfinder/README.md',
            $targetConnectorPath . '/README.md'
        ]);

        $fs->deleteDirectory($targetPublicPath . '/ckfinder/core');
        $fs->deleteDirectory($targetPublicPath . '/ckfinder/userfiles');


        $this->info('Done. Happy coding!');
    }
}
