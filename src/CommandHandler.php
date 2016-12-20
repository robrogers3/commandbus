<?php

namespace RobRogers\CommandBus;

interface CommandHandler
{
    public function handle($command);
}
