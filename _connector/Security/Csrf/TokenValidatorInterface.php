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

namespace CKSource\CKFinder\Security\Csrf;

use Symfony\Component\HttpFoundation\Request;

/**
 * The TokenValidatorInterface interface.
 *
 * An interface for CSRF token validators.
 */
interface TokenValidatorInterface
{
    /**
     * Checks if the request contains a valid CSRF token.
     *
     * @return bool `true` if the token is valid, `false` otherwise
     */
    public function validate(Request $request);
}
