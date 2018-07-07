<?php

namespace CKSource\CKFinderBridge\Controller;

use CKSource\CKFinder\CKFinder;
use \Illuminate\Routing\Controller;
use Psr\Container\ContainerInterface;
use Illuminate\Http\Request;

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

        return $connector->handle($request);
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
