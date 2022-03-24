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
use CKSource\CKFinder\Filesystem\Folder\WorkingFolder;

/**
 * The DeleteFolderEvent event class.
 */
class DeleteFolderEvent extends CKFinderEvent
{
    /**
     * The working folder that is going to be deleted.
     *
     * @var WorkingFolder
     */
    protected $workingFolder;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app, WorkingFolder $workingFolder)
    {
        $this->workingFolder = $workingFolder;

        parent::__construct($app);
    }

    /**
     * Returns the working folder that is going to be deleted.
     *
     * @return WorkingFolder
     */
    public function getWorkingFolder()
    {
        return $this->workingFolder;
    }
}
