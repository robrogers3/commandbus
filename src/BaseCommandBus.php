<?php

namespace RobRogers\CommandBus;

class BaseCommandBus implements CommandBus
{
    protected $commandTranslator;

    private $app;

    /**
     * CommandBus constructor.
     * @param CommandTranslator $commandTranslator
     */
    public function __construct(CommandTranslator $commandTranslator)
    {
        $this->commandTranslator = $commandTranslator;
        $this->app = \App::Make('App');
    }

    //$command is just some dto
    public function execute($command)
    {
        $handler = $this->commandTranslator->toCommandHandler($command);

        $this->app->make($handler)->handle($command);
    }
}
