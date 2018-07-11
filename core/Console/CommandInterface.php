<?php

namespace Core\Console;


interface CommandInterface
{
    public function execute();

    public function description();
}