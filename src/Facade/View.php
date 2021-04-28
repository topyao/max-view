<?php


namespace Max\Facade;

/**
 * @method static render(string $template, $params = [])
 * Class View
 * @package Max\Facade
 */
class View extends Facade
{

    protected static function getFacadeClass()
    {
        return 'view';
    }

}
