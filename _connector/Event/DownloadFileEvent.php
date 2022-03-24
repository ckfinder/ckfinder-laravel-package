<?php

/*
 * CKFinder
 * ========
 * https://ckeditor.com/ckfinder/
 * Copyright (c) 2007-2021, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

namespace CKSource\CKFinder\Event;

use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Filesystem\File\DownloadedFile;

/**
 * The DownloadFileEvent event class.
 */
class DownloadFileEvent extends CKFinderEvent
{
    /**
     * @var DownloadedFile
     */
    protected $downloadedFile;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app, DownloadedFile $downloadedFile)
    {
        $this->downloadedFile = $downloadedFile;

        parent::__construct($app);
    }

    /**
     * Returns the downloaded file object.
     *
     * @return DownloadedFile
     *
     * @deprecated please use getFile() instead
     */
    public function getDownloadedFile()
    {
        return $this->downloadedFile;
    }

    /**
     * Returns the downloaded file object.
     *
     * @return DownloadedFile
     */
    public function getFile()
    {
        return $this->downloadedFile;
    }
}
