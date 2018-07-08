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
            'widget' => 'integration',
            'popups' => 'integration',
            'modals' => 'integration',
            'full-page' => 'integration',
            'full-page-open' => 'integration',

            'ckeditor' => 'ckeditor',

            'skins-moono' => 'skins',
            'skins-jquery-mobile' => 'skins',

            'user-interface-default' => 'user-interface',
            'user-interface-compact' => 'user-interface',
            'user-interface-mobile' => 'user-interface',
            'user-interface-listview' => 'user-interface',

            'localization' => 'localization',

            'other-read-only' => 'other',
            'other-custom-configuration' => 'other',

            'plugin-examples' => 'plugin-examples',
        ];

        $section = null;
        $sample = null;

        if (array_key_exists($example, $knownExamples)) {
            $sample = $example;
            $section = $knownExamples[$sample];

            return view('ckfinder::samples/'.$example, ['section' => $section, 'sample' => $sample]);
        }

        return view('ckfinder::samples/index', ['section' => $section, 'sample' => $sample]);
    }
}
