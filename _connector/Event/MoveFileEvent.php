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
use CKSource\CKFinder\Filesystem\File\MovedFile;

/**
 * The MoveFileEvent event class.
 */
class MoveFileEvent extends CKFinderEvent
{
    /**
     * @var MovedFile
     */
    protected $movedFile;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app, MovedFile $movedFile)
    {
        $this->movedFile = $movedFile;

        parent::__construct($app);
    }

    /**
     * Returns the moved file object.
     *
     * @return MovedFile
     *
     * @deprecated please use getFile() instead
     */
    public function getMovedFile()
    {
        return $this->movedFile;
    }

    /**
     * Returns the moved file object.
     *
     * @return MovedFile
     */
    public function getFile()
    {
        return $this->movedFile;
    }
}
