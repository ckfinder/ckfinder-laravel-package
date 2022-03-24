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
use CKSource\CKFinder\Filesystem\File\UploadedFile;

/**
 * The FileUploadEvent event class.
 */
class FileUploadEvent extends CKFinderEvent
{
    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app, UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        parent::__construct($app);
    }

    /**
     * Returns the uploaded file object.
     *
     * @return UploadedFile
     *
     * @deprecated please use getFile() instead
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Returns the uploaded file object.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->uploadedFile;
    }
}
