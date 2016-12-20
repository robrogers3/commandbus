<?php

namespace RobRogers\CommandBus;

use Exception;

class CommandTranslator
{
    /**
     * @param $command
     * PostJobListCommand => PostJabListingCommandHandler
     * @return mixed
     * @throws Exception
     */
    public function toCommandHandler($command)
    {
        $handler = str_replace('Command','CommandHandler', get_class($command));

        if (!class_exists($handler)) {
            $message = "Command handler [$handler] does not exist.";

            throw new Exception($message);
        }

        return $handler;
    }
}
