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

namespace CKSource\CKFinder\Event;

use CKSource\CKFinder\CKFinder;
use Symfony\Component\HttpFoundation\Response;

/**
 * The AfterCommandEvent event class.
 */
class AfterCommandEvent extends CKFinderEvent
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $commandName;

    /**
     * The response object received from the command.
     *
     * @var Response
     */
    protected $response;

    /**
     * Constructor.
     *
     * @param string $commandName
     */
    public function __construct(CKFinder $app, $commandName, Response $response)
    {
        $this->commandName = $commandName;
        $this->response = $response;

        parent::__construct($app);
    }

    /**
     * Returns the response object received from the command.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the response to be returned.
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
