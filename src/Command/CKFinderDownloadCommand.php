<?php

namespace CKSource\CKFinderBridge\Command;

use Illuminate\Console\Command;

class CKFinderDownloadCommand extends Command
{
    /** {@inheritdoc} */
    protected $name = 'ckfinder:download';

    /** {@inheritdoc} */
    protected $description = 'Downloads the CKFinder distribution package and extracts assets.';

    /**
     * Handles command execution.
     */
    public function handle()
    {
        $this->info('Downloading CKFinder...');
    }
}