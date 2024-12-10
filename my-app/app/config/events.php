<?php

use Spiral\Events\Processor\AttributeProcessor;
use Spiral\Events\Processor\ConfigProcessor;

return [
    /**
     * -------------------------------------------------------------------------
     *  Processors
     * -------------------------------------------------------------------------
     *
     * Array of all available processors.
     */
    'processors' => [
        AttributeProcessor::class,
        ConfigProcessor::class,
    ],
];
