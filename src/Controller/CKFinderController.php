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
     * Use custom middleware to handle custom authentication and redirects.
     */
    public function __construct()
    {
        $authenticationMiddleware = config('ckfinder.authentication');

        if(!is_callable($authenticationMiddleware)) {
            if(isset($authenticationMiddleware) && is_string($authenticationMiddleware)) {
                $this->middleware($authenticationMiddleware);
            } else {
                $this->middleware(\CKSource\CKFinderBridge\CKFinderMiddleware::class);
            }
        }
    }

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
     * Action that displays CKFinder browser.
     *
     * @return string
     */
    public function browserAction(ContainerInterface $container, Request $request)
    {
        return view('ckfinder::browser');
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
        $example = strtolower($example);

        $knownExamples = [
            'integration'     => ['widget', 'popups', 'modals', 'full-page', 'full-page-open'],
            'ckeditor'        => ['ckeditor'],
            'skins'           => ['skins-moono', 'skins-jquery-mobile'],
            'user-interface'  => ['user-interface-default', 'user-interface-compact', 'user-interface-mobile', 'user-interface-listview'],
            'localization'    => ['localization'],
            'other'           => ['other-read-only', 'other-custom-configuration'],
            'plugin-examples' => ['plugin-examples'],
        ];

        $navInfo = ['section' => null, 'sample' => null];

        foreach ($knownExamples as $section => $examples) {
            if (in_array($example, $examples)) {
                $navInfo['section'] = $section;
                $navInfo['sample'] = $example;

                return view('ckfinder::samples/'.$example, $navInfo);
            }
        }

        return view('ckfinder::samples/index', $navInfo);
    }
}
