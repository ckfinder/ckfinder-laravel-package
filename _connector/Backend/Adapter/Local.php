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

namespace CKSource\CKFinder\Backend\Adapter;

use CKSource\CKFinder\Acl\Acl;
use CKSource\CKFinder\Acl\Permission;
use CKSource\CKFinder\Backend\Backend;
use CKSource\CKFinder\Exception\AccessDeniedException;
use CKSource\CKFinder\Exception\FolderNotFoundException;
use CKSource\CKFinder\Filesystem\Path;
use CKSource\CKFinder\ResourceType\ResourceType;
use CKSource\CKFinder\Utils;
use SplFileInfo;

/**
 * Local file system adapter.
 *
 * A wrapper class for \League\Flysystem\Adapter\Local with
 * additions for `chmod` permissions management and conversions
 * between the file system and connector file name encoding.
 */
class Local extends \League\Flysystem\Adapter\Local
{
    /**
     * Backend configuration node.
     *
     * @var array
     */
    protected $backendConfig;

    /**
     * Constructor.
     *
     * @throws \Exception if the root folder is not writable
     */
    public function __construct(array $backendConfig)
    {
        $this->backendConfig = $backendConfig;

        if (!isset($backendConfig['root']) || empty($backendConfig['root'])) {
            $baseUrl = $backendConfig['baseUrl'];
            $baseUrl = preg_replace('|^http(s)?://[^/]+|i', '', $baseUrl);
            $backendConfig['root'] = Path::combine(Utils::getRootPath(), Utils::decodeURLParts($baseUrl));
        }

        if (!is_dir($backendConfig['root'])) {
            @mkdir($backendConfig['root'], $backendConfig['chmodFolders'], true);
            if (!is_dir($backendConfig['root'])) {
                throw new FolderNotFoundException(sprintf('The root folder of backend "%s" not found (%s)', $backendConfig['name'], $backendConfig['root']));
            }
        }

        if (!is_readable($backendConfig['root'])) {
            throw new AccessDeniedException(sprintf('The root folder of backend "%s" is not readable (%s)', $backendConfig['name'], $backendConfig['root']));
        }

        parent::__construct($backendConfig['root'], LOCK_EX, self::SKIP_LINKS, [
            'file' => ['public' => $backendConfig['chmodFiles']],
            'dir' => ['public' => $backendConfig['chmodFolders']],
        ]);
    }

    /**
     * Creates a stream for writing to a file.
     *
     * @param string $path
     *
     * @return resource
     */
    public function createWriteStream($path)
    {
        $location = $this->applyPathPrefix($path);
        $this->ensureDirectory(\dirname($location));
        $chmodFiles = $this->backendConfig['chmodFiles'];

        if (!$stream = fopen($location, 'a+')) {
            return false;
        }

        $oldUmask = umask(0);
        chmod($location, $chmodFiles);
        umask($oldUmask);

        return $stream;
    }

    /**
     * Checks if the directory contains subdirectories.
     *
     * @param string $clientPath
     *
     * @return bool
     */
    public function containsDirectories(Backend $backend, ResourceType $resourceType, $clientPath, Acl $acl)
    {
        $location = rtrim($this->applyPathPrefix(Path::combine($resourceType->getDirectory(), $clientPath)), '/\\').'/';

        if (!is_dir($location) || (false === $fh = @opendir($location))) {
            return false;
        }

        $hasChildren = false;
        $resourceTypeName = $resourceType->getName();
        $clientPath = rtrim($clientPath, '/\\').'/';

        while (false !== ($filename = readdir($fh))) {
            if ('.' === $filename || '..' === $filename) {
                continue;
            }

            if (is_dir($location.$filename)) {
                if (!$acl->isAllowed($resourceTypeName, $clientPath.$filename, Permission::FOLDER_VIEW)) {
                    continue;
                }
                if ($backend->isHiddenFolder($filename)) {
                    continue;
                }
                $hasChildren = true;

                break;
            }
        }

        closedir($fh);

        return $hasChildren;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDir($dirname)
    {
        $location = $this->applyPathPrefix($dirname);

        if ($this->backendConfig['followSymlinks'] && is_link($location)) {
            return unlink($location);
        }

        return parent::deleteDir($dirname);
    }

    /**
     * {@inheritdoc}
     */
    protected function normalizeFileInfo(SplFileInfo $file)
    {
        if ($this->backendConfig['followSymlinks']) {
            return $this->mapFileInfo($file);
        }

        return parent::normalizeFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapFileInfo(SplFileInfo $file)
    {
        $normalized = parent::mapFileInfo($file);

        if ($this->backendConfig['followSymlinks'] && $file->isLink()) {
            $normalized['type'] = $file->isDir() ? 'dir' : 'file';

            if ('file' === $normalized['type']) {
                $normalized['size'] = $file->getSize();
            }
        }

        return $normalized;
    }
}
