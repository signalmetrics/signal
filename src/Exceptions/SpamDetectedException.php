<?php

namespace Signalmetrics\Signal\Exceptions;

class SpamDetectedException extends \Exception
{
    use BypassViewHandler;

    public function __construct()
    {
        parent::__construct(
            'Signal encountered spam and rejected storing the submission.'
        );
    }
}