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
use CKSource\CKFinder\Command\CommandAbstract;

/**
 * The BeforeCommandEvent event class.
 */
class BeforeCommandEvent extends CKFinderEvent
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $commandName;

    /**
     * The object of the command to be executed.
     *
     * @var CommandAbstract
     */
    protected $commandObject;

    /**
     * Constructor.
     *
     * @param string $commandName
     */
    public function __construct(CKFinder $app, $commandName, CommandAbstract $commandObject)
    {
        $this->commandName = $commandName;
        $this->commandObject = $commandObject;

        parent::__construct($app);
    }

    /**
     * Returns the command object.
     *
     * @return CommandAbstract
     */
    public function getCommandObject()
    {
        return $this->commandObject;
    }

    /**
     * Sets the object of the command to be executed.
     */
    public function setCommandObject(CommandAbstract $commandObject)
    {
        $this->commandObject = $commandObject;
    }

    /**
     * Returns the name of the command.
     *
     * @return string command name
     */
    public function getCommandName()
    {
        return $this->commandName;
    }
}
