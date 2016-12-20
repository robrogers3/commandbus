<?php

namespace RobRogers\CommandBus;

interface CommandBus
{
    public function execute($command);
}
