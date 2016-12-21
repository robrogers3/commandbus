<?php

namespace RobRogers\CommandBus;

class BaseCommandBus implements CommandBus
{
    protected $commandTranslator;

    /**
     * CommandBus constructor.
     * @param CommandTranslator $commandTranslator
     */
    public function __construct(CommandTranslator $commandTranslator)
    {
        $this->commandTranslator = $commandTranslator;

    }

    /**
     * @param $command
     *
     * $command is a simple DTO
     */
    public function execute($command)
    {
        /** @var CommandHandler $handler */
        $handler = $this->commandTranslator->toCommandHandler($command);

        $handler = \App::Make($handler);

        $handler->handle($command);
    }
}
