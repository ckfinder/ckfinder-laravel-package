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
     */
    public function requestAction(ContainerInterface $container, Request $request)
    {
        /** @var CKFinder $connector */
        $connector = $container->get('ckfinder.connector');

        $response = $connector->handle($request);

        return $response;
    }
}
