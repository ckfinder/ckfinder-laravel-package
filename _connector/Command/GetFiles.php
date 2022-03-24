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

namespace CKSource\CKFinder\Command;

use CKSource\CKFinder\Acl\Permission;
use CKSource\CKFinder\Filesystem\Folder\WorkingFolder;
use CKSource\CKFinder\Utils;

class GetFiles extends CommandAbstract
{
    protected $requires = [Permission::FILE_VIEW];

    public function execute(WorkingFolder $workingFolder)
    {
        $data = new \stdClass();
        $files = $workingFolder->listFiles();

        $data->files = [];

        foreach ($files as $file) {
            $fileObject = [
                'name' => $file['basename'],
                'date' => Utils::formatDate($file['timestamp']),
                'size' => Utils::formatSize($file['size']),
            ];

            $data->files[] = $fileObject;
        }

        // Sort files
        usort($data->files, function ($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });

        return $data;
    }
}
