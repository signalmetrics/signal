<?php

namespace Signalmetrics\Signal\DTO;

class XYDimension {


    public function __construct(
        public ?int $x = null,
        public ?int $y = null,
    )
    {
    }
}