<?php

namespace RobRogers\CommandBus;

use Illuminate\Foundation\Application;

class BaseCommandBus implements CommandBus
{
    protected $commandTranslator;

    /**
     * @var Application
     */
    private $app;

    /**
     * CommandBus constructor.
     * @param Application $app
     * @param CommandTranslator $commandTranslator
     */
    public function __construct(Application $app,  CommandTranslator $commandTranslator)
    {
        $this->commandTranslator = $commandTranslator;
        $this->app = $app;
    }

    //$command is just some dto
    public function execute($command)
    {
        $handler = $this->commandTranslator->toCommandHandler($command);

        $this->app->make($handler)->handle($command);
    }
}
