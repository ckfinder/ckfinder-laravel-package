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
use CKSource\CKFinder\Filesystem\File\CopiedFile;

/**
 * The CopyFileEvent event class.
 */
class CopyFileEvent extends CKFinderEvent
{
    /**
     * @var CopiedFile
     */
    protected $copiedFile;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app, CopiedFile $copiedFile)
    {
        $this->copiedFile = $copiedFile;

        parent::__construct($app);
    }

    /**
     * Returns the copied file object.
     *
     * @return CopiedFile
     *
     * @deprecated please use getFile() instead
     */
    public function getCopiedFile()
    {
        return $this->copiedFile;
    }

    /**
     * Returns the copied file object.
     *
     * @return CopiedFile
     */
    public function getFile()
    {
        return $this->copiedFile;
    }
}
