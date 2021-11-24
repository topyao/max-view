<?php

namespace Max\View\Engines\Blade;

class Raw
{
    protected string $raw;

    public function __construct(string $raw)
    {
        $this->raw = $raw;
    }

    public function __toString()
    {
        return base64_decode($this->raw);
    }
}
