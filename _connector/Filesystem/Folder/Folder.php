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

namespace CKSource\CKFinder\Filesystem\Folder;

use CKSource\CKFinder\Filesystem\File\File;
use CKSource\CKFinder\Filesystem\Path;
use CKSource\CKFinder\ResourceType\ResourceType;

/**
 * The Folder class.
 *
 * Represents a folder in the file system.
 */
class Folder
{
    /**
     * @var ResourceType
     */
    protected $resourceType;

    /**
     * Backend relative path (includes the resource type directory).
     *
     * @var string
     */
    protected $path;

    /**
     * @param ResourceType $resourceType resource type
     * @param string       $path         resource type relative path
     */
    public function __construct(ResourceType $resourceType, $path)
    {
        $this->resourceType = $resourceType;
        $this->path = Path::combine($resourceType->getDirectory(), $path);
    }

    /**
     * Checks whether `$folderName` is a valid folder name. Returns `true` on success.
     *
     * @param string $folderName
     * @param bool   $disallowUnsafeCharacters
     *
     * @return bool
     */
    public static function isValidName($folderName, $disallowUnsafeCharacters)
    {
        if ($disallowUnsafeCharacters) {
            if (false !== strpos($folderName, '.')) {
                return false;
            }
        }

        return File::isValidName($folderName, $disallowUnsafeCharacters);
    }
}
