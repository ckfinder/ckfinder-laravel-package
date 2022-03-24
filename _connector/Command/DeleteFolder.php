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
use CKSource\CKFinder\Event\CKFinderEvent;
use CKSource\CKFinder\Event\DeleteFolderEvent;
use CKSource\CKFinder\Exception\AccessDeniedException;
use CKSource\CKFinder\Exception\InvalidRequestException;
use CKSource\CKFinder\Filesystem\Folder\WorkingFolder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class DeleteFolder extends CommandAbstract
{
    protected $requestMethod = Request::METHOD_POST;

    protected $requires = [Permission::FOLDER_DELETE];

    public function execute(WorkingFolder $workingFolder, EventDispatcher $dispatcher)
    {
        // The root folder cannot be deleted.
        if ('/' === $workingFolder->getClientCurrentFolder()) {
            throw new InvalidRequestException('Cannot delete resource type root folder');
        }

        $deleteFolderEvent = new DeleteFolderEvent($this->app, $workingFolder);

        $dispatcher->dispatch($deleteFolderEvent, CKFinderEvent::DELETE_FOLDER);

        $deleted = false;

        if (!$deleteFolderEvent->isPropagationStopped()) {
            $deleted = $workingFolder->delete();
        }

        if (!$deleted) {
            throw new AccessDeniedException();
        }

        return ['deleted' => (int) $deleted];
    }
}
