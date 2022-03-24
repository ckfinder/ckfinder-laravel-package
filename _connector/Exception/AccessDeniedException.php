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

namespace CKSource\CKFinder\Exception;

use CKSource\CKFinder\Error;
use Symfony\Component\HttpFoundation\Response;

/**
 * The "access denied" exception.
 *
 * Thrown when file system permissions do not allow to perform an operation
 * such as accessing a directory or writing a file.
 */
class AccessDeniedException extends CKFinderException
{
    protected $httpStatusCode = Response::HTTP_FORBIDDEN;

    /**
     * Constructor.
     *
     * @param string     $message    the exception message
     * @param array      $parameters the parameters passed for translation
     * @param \Exception $previous   the previous exception
     */
    public function __construct($message = 'Access denied', $parameters = [], \Exception $previous = null)
    {
        parent::__construct($message, Error::ACCESS_DENIED, $parameters, $previous);
    }
}
