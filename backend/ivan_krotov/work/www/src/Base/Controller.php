<?php

namespace root\Base;

abstract class Controller
{
    static ?self $instance = null;

    abstract public static function getInstance(): self;
}