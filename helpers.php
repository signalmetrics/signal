<?php

namespace Signalmetrics;

use ReflectionClass;
use Illuminate\Support\Str;

function invade($obj)
{
    return new class($obj) {

        public $obj;
        public $reflected;

        public function __construct($obj)
        {
            $this->obj = $obj;
            $this->reflected = new ReflectionClass($obj);
        }

        public function &__get($name)
        {
            $getProperty = function &() use ($name) {
                return $this->{$name};
            };

            $getProperty = $getProperty->bindTo($this->obj, get_class($this->obj));

            return $getProperty();
        }

        public function __set($name, $value)
        {
            $setProperty = function () use ($name, &$value) {
                $this->{$name} = $value;
            };

            $setProperty = $setProperty->bindTo($this->obj, get_class($this->obj));

            $setProperty();
        }

        public function __call($name, $params)
        {
            $method = $this->reflected->getMethod($name);

            return $method->invoke($this->obj, ...$params);
        }

    };
}