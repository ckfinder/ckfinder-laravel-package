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

namespace CKSource\CKFinder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

class ArgumentResolver implements ArgumentResolverInterface
{
    /**
     * The app instance.
     *
     * @var CKFinder
     */
    protected $app;

    /**
     * Constructor.
     */
    public function __construct(CKFinder $app)
    {
        $this->app = $app;
    }

    /**
     * This method is used to inject objects to controllers.
     * It depends on arguments taken by the executed controller callable.
     *
     * Supported injected types:
     * Request             - current request object
     * CKFinder            - application object
     * EventDispatcher     - event dispatcher
     * Config              - Config object
     * Acl                 - Acl object
     * BackendManager      - BackendManager object
     * ResourceTypeFactory - ResourceTypeFactory object
     * WorkingFolder       - WorkingFolder object
     *
     * @param Request $request request object
     *
     * @return array arguments used during the command callable execution
     */
    public function getArguments(Request $request, callable $command)
    {
        $r = new \ReflectionMethod($command[0], $command[1]);

        $parameters = $r->getParameters();

        $arguments = [];

        foreach ($parameters as $param) {
            /** @var \ReflectionParameter $param */
            if ($reflectionClass = new \ReflectionClass($param->getType()->getName())) {
                if ($reflectionClass->isInstance($this->app)) {
                    $arguments[] = $this->app;
                } elseif ($reflectionClass->isInstance($request)) {
                    $arguments[] = $request;
                } elseif ($reflectionClass->isInstance($this->app['dispatcher'])) {
                    $arguments[] = $this->app['dispatcher'];
                } elseif ($reflectionClass->isInstance($this->app['config'])) {
                    $arguments[] = $this->app['config'];
                }

                // Don't check isInstance to avoid unnecessary instantiation
                $classShortName = $reflectionClass->getShortName();

                switch ($classShortName) {
                    case 'BackendFactory':
                        $arguments[] = $this->app['backend_factory'];

                        break;
                    case 'ResourceTypeFactory':
                        $arguments[] = $this->app['resource_type_factory'];

                        break;
                    case 'Acl':
                        $arguments[] = $this->app['acl'];

                        break;
                    case 'WorkingFolder':
                        $arguments[] = $this->app['working_folder'];

                        break;
                    case 'ThumbnailRepository':
                        $arguments[] = $this->app['thumbnail_repository'];

                        break;
                    case 'ResizedImageRepository':
                        $arguments[] = $this->app['resized_image_repository'];

                        break;
                    case 'CacheManager':
                        $arguments[] = $this->app['cache'];

                        break;
                }
            } else {
                $arguments[] = null;
            }
        }

        return $arguments;
    }
}
