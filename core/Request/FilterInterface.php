<?php

namespace Core\Request;


interface FilterInterface
{
    public function before();

    public function after();
}