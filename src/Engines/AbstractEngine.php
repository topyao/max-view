<?php
declare(strict_types=1);

namespace Max\View\Engines;

use Max\View\Contracts\ViewEngineInterface;

/**
 * Class AbstractEngine
 *
 * @package Max\View
 */
abstract class AbstractEngine implements ViewEngineInterface
{
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        foreach ($options as $key => $option) {
            $this->{$key} = $option;
        }
    }
}
