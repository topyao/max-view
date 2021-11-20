<?php

namespace Max\View\Contracts;

interface ViewEngineInterface
{
    public function render(string $template, array $arguments = []);
}