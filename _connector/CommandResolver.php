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

use CKSource\CKFinder\Command\CommandAbstract;
use CKSource\CKFinder\Event\BeforeCommandEvent;
use CKSource\CKFinder\Event\CKFinderEvent;
use CKSource\CKFinder\Exception\InvalidCommandException;
use CKSource\CKFinder\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * The command resolver class.
 *
 * The purpose of this class is to resolve which CKFinder command should be executed
 * for the current request. This process is based on a value passed in the
 * <code>$_GET['command']</code> request variable.
 */
class CommandResolver implements ControllerResolverInterface
{
    /**
     * The name of the method to execute in commands classes.
     */
    const COMMAND_EXECUTE_METHOD = 'execute';

    /**
     * The commands class namespace.
     *
     * @var string
     */
    protected $commandsNamespace;

    /**
     * The plugins class namespace.
     *
     * @var string
     */
    protected $pluginsNamespace;

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
     * Sets the namespace used to resolve commands.
     *
     * @param string $namespace
     */
    public function setCommandsNamespace($namespace)
    {
        $this->commandsNamespace = $namespace;
    }

    /**
     * Sets the namespace used to resolve plugin commands.
     *
     * @param string $namespace
     */
    public function setPluginsNamespace($namespace)
    {
        $this->pluginsNamespace = $namespace;
    }

    /**
     * This method looks for a 'command' request attribute. An appropriate class
     * is then instantiated and used to build a callable.
     *
     * @param Request $request current Request instance
     *
     * @throws InvalidCommandException   if a valid command cannot be found
     * @throws MethodNotAllowedException if a command was called using an invalid HTTP method
     *
     * @return callable callable built to execute the command
     */
    public function getController(Request $request)
    {
        $commandName = ucfirst((string) $request->get('command'));

        /** @var Command\CommandAbstract $commandObject */
        $commandObject = null;

        // First check for regular command class
        $commandClassName = $this->commandsNamespace.$commandName;

        if (class_exists($commandClassName)) {
            $reflectedClass = new \ReflectionClass($commandClassName);
            if (!$reflectedClass->isInstantiable()) {
                throw new InvalidCommandException(sprintf('CKFinder command class %s is not instantiable', $commandClassName));
            }
            $commandObject = new $commandClassName($this->app);
        }

        // If not found - check if command plugin with given name exists
        if (null === $commandObject) {
            $plugin = $this->app->getPlugin($commandName);
            if ($plugin instanceof CommandAbstract) {
                $commandObject = $plugin;
            }
        }

        if (null === $commandObject) {
            throw new InvalidCommandException(sprintf('CKFinder command %s not found', $commandName));
        }

        if (!$commandObject instanceof CommandAbstract) {
            throw new InvalidCommandException(sprintf('CKFinder command must be a subclass of CommandAbstract (%s given)', \get_class($commandObject)));
        }

        if (!method_exists($commandObject, self::COMMAND_EXECUTE_METHOD)) {
            throw new InvalidCommandException(sprintf("CKFinder command class %s doesn't contain required 'execute' method", $commandClassName));
        }

        if ($commandObject->getRequestMethod() !== $request->getMethod()) {
            throw new MethodNotAllowedException(sprintf(
                'CKFinder command %s expects to be called with %s HTTP request. Actual method: %s',
                $commandName,
                $commandObject->getRequestMethod(),
                $request->getMethod()
            ));
        }

        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
        $dispatcher = $this->app['dispatcher'];
        $beforeCommandEvent = new BeforeCommandEvent($this->app, $commandName, $commandObject);

        $eventName = CKFinderEvent::BEFORE_COMMAND_PREFIX.lcfirst($commandName);

        $dispatcher->dispatch($beforeCommandEvent, $eventName);

        $commandObject = $beforeCommandEvent->getCommandObject();

        $commandObject->checkPermissions();

        return [$commandObject, self::COMMAND_EXECUTE_METHOD];
    }
}
