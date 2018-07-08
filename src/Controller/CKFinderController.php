<?php

namespace CKSource\CKFinderBridge\Controller;

use CKSource\CKFinder\CKFinder;
use \Illuminate\Routing\Controller;
use Psr\Container\ContainerInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Controller for handling requests to CKFinder connector.
 */
class CKFinderController extends Controller
{
    /**
     * Action that handles all CKFinder requests.
     *
     * @param ContainerInterface $container
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction(ContainerInterface $container, Request $request)
    {
        /** @var CKFinder $connector */
        $connector = $container->get('ckfinder.connector');

        // If debug mode is enabled then do not catch exceptions and pass them directly to Laravel.
        $enableDebugMode = config('ckfinder.debug');

        return $connector->handle($request, HttpKernelInterface::MASTER_REQUEST, !$enableDebugMode);
    }

    /**
     * Action for CKFinder usage examples.
     *
     * To browse examples, please uncomment ckfinder_examples route in
     * vendor/ckfinder/ckfinder-laravel-package/src/routes.php
     *
     * @param string|null $example
     */
    public function examplesAction($example = null)
    {
        var_dump($example);exit;
    }
}
