<?php

namespace App\Movies\Exceptions;

class ServiceUnavailableException extends \Exception
{
    /** @var int */
    protected $code = 503;
}