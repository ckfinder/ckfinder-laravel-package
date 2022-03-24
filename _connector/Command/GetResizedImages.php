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
use CKSource\CKFinder\Cache\CacheManager;
use CKSource\CKFinder\Config;
use CKSource\CKFinder\Exception\InvalidRequestException;
use CKSource\CKFinder\Filesystem\Folder\WorkingFolder;
use CKSource\CKFinder\Filesystem\Path;
use CKSource\CKFinder\Image;
use CKSource\CKFinder\ResizedImage\ResizedImageRepository;
use Symfony\Component\HttpFoundation\Request;

class GetResizedImages extends CommandAbstract
{
    protected $requires = [Permission::FILE_VIEW];

    public function execute(Request $request, WorkingFolder $workingFolder, ResizedImageRepository $resizedImageRepository, Config $config, CacheManager $cache)
    {
        $fileName = (string) $request->get('fileName');
        $sizes = (string) $request->get('sizes');

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!Image::isSupportedExtension($ext)) {
            throw new InvalidRequestException('Invalid file extension');
        }

        if ($sizes) {
            $sizes = explode(',', $sizes);
            if (array_diff($sizes, array_keys($config->get('images.sizes')))) {
                throw new InvalidRequestException(sprintf('Invalid size requested'));
            }
        }

        $data = [];

        $cachedInfo = $cache->get(
            Path::combine(
                $workingFolder->getResourceType()->getName(),
                $workingFolder->getClientCurrentFolder(),
                $fileName
            )
        );

        if ($cachedInfo && isset($cachedInfo['width'], $cachedInfo['height'])) {
            $data['originalSize'] = sprintf('%dx%d', $cachedInfo['width'], $cachedInfo['height']);
        }

        $resizedImages = $resizedImageRepository->getResizedImagesList(
            $workingFolder->getResourceType(),
            $workingFolder->getClientCurrentFolder(),
            $fileName,
            $sizes ?: []
        );

        $data['resized'] = $resizedImages;

        return $data;
    }
}
