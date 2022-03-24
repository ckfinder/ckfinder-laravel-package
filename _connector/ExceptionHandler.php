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

use CKSource\CKFinder\Exception\CKFinderException;
use CKSource\CKFinder\Response\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The exception handler class.
 *
 * All errors are resolved here and passed to the response.
 */
class ExceptionHandler implements EventSubscriberInterface
{
    /**
     * Flag used to enable the debug mode.
     *
     * @var bool
     */
    protected $debug;

    /**
     * LoggerInterface.
     *
     * @var LoggerInterface
     */
    protected $logger;

    protected $translator;

    /**
     * Constructor.
     *
     * @param Translator      $translator translator object
     * @param bool            $debug      `true` if debug mode is enabled
     * @param LoggerInterface $logger     logger
     */
    public function __construct(Translator $translator, $debug = false, LoggerInterface $logger = null)
    {
        $this->translator = $translator;
        $this->debug = $debug;
        $this->logger = $logger;

        if ($debug) {
            set_error_handler([$this, 'errorHandler']);
        }
    }

    public function onCKFinderError(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        $exceptionCode = $throwable->getCode() ?: Error::UNKNOWN;

        $replacements = [];

        $httpStatusCode = 200;

        if ($throwable instanceof CKFinderException) {
            $replacements = $throwable->getParameters();
            $httpStatusCode = $throwable->getHttpStatusCode();
        }

        $message =
            Error::CUSTOM_ERROR === $exceptionCode
                ? $throwable->getMessage()
                : $this->translator->translateErrorMessage($exceptionCode, $replacements);

        $response = new JsonResponse();
        $response->withError($exceptionCode, $message);

        $event->setThrowable(new HttpException($httpStatusCode));

        $event->setResponse($response);

        if ($this->debug && $this->logger) {
            $this->logger->error($throwable);
        }

        if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
            throw $throwable;
        }
    }

    /**
     * Custom error handler to catch all errors in the debug mode.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     *
     * @throws \Exception
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $wrapperException = new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        $this->logger->warning($wrapperException);

        if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
            throw $wrapperException;
        }
    }

    /**
     * Returns all events and callbacks.
     *
     * @see <a href="http://api.symfony.com/2.5/Symfony/Component/EventDispatcher/EventSubscriberInterface.html">EventSubscriberInterface</a>
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onCKFinderError', -255]];
    }
}
