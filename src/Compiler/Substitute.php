<?php

namespace Max\View\Compiler;

class Substitute
{

    protected static $sections = [];

    protected static $parent;

    protected static $rules = [
        '/@extends\([\'"](.*?)[\'"]\)/'                                                          => [self::class, 'compileExtends'],
        '/@yield\([\'"]?(.*?)[\'"]?\)/'                                                          => [self::class, 'compileYield'],
        '/@php([\s\S]*?)@endphp/'                                                                => [self::class, 'compilePHP'],
        '/\{\{(.*?)\}\}/'                                                                        => [self::class, 'compileEcho'],
        '/@include\([\'"](.*?)[\'"]\)/'                                                          => [self::class, 'compileInclude'],
        '/(@if|@unless|@empty|@isset)\((.*)\)([\s\S]*?)(@endif|@endunless|@endempty|@endisset)/' => [self::class, 'compileEndif'],
        '/@foreach\((.*?)\)([\s\S]*?)@endforeach/'                                               => [self::class, 'compileForeach'],
        '/@for\((.*?)\)([\s\S]*?)@endfor/'                                                       => [self::class, 'compileFor'],
        '/@switch\((.*?)\)([\s\S]*?)@endswitch/'                                                 => [self::class, 'compileSwitch'],
        '/@section\([\'"](.*?)[\'"]\)([\s\S]*?)@endsection/'                                     => [self::class, 'compileSection'],
    ];

    public static function compileYield($matches)
    {
        $value = explode('\',\'', str_replace(' ', '', $matches[1]));

        return trim(self::$sections[$value[0]] ?? ($value[1] ?? ''));
    }

    public static function compileSection($matches)
    {
        self::$sections[$matches[1]] = $matches[2];
    }

    public static function compileExtends($matches)
    {
        $view         = config('view.path') . '/' . $matches[1] . config('view.max.options.suffix');
        self::$parent = $view;
    }

    public static function compileInclude($matches)
    {
        $view = config('view.path') . '/' . $matches[1] . config('view.max.options.suffix');
        return self::com(file_get_contents($view));
    }

    public static function compileEndif($matches)
    {
        [$statement, $condition, $content] = array_slice($matches, 1);
        switch (str_replace('@', '', $statement)) {
            case 'if':
                $patterns = [
                    '/@elseif\((.*)\)/' => '<?php elseif(\\1): ?>',
                    '/@else/'           => '<?php else: ?>',
                ];
                $content  = preg_replace(array_keys($patterns), array_values($patterns), $content);
                break;
            case 'unless':
                $condition = "!($condition)";
                break;
            case 'empty':
                $condition = "empty($condition)";
                break;
            case 'isset':
                $condition = "isset($condition)";
                break;
        }
        return sprintf('<?php if (%s): ?>%s<?php endif; ?>', $condition, $content);
    }

    public static function compileEcho($matches)
    {
        $value = $matches[1];
        if (false !== strpos($value, '|')) {
            $params = explode('|', $value);
            $value  = array_shift($params);
            while ($function = array_pop($params)) {
                $value = sprintf('%s(%s)', trim($function), (string)$value);
            }
        }
        return sprintf('<?php echo %s; ?>', $value);
    }

    public static function compileForeach($matches)
    {
        [$condition, $segment] = array_slice($matches, 1);
        return sprintf('<?php foreach (%s): ?>%s<?php endforeach; ?>', $condition, $segment);
    }

    public static function compileFor($matches)
    {
        [$condition, $segment] = array_slice($matches, 1);
        return sprintf('<?php for (%s): ?>%s<?php endfor; ?>', $condition, $segment);
    }

    public static function compilePHP($matches)
    {
        return sprintf("<?php%s?>", $matches[1]);
    }

    public static function compileSwitch($matches)
    {
        [$condition, $segment] = array_slice($matches, 1);
        $patterns = [
            '/@case\((.*)\)/' => "<?php case \\1: ?>",
            '/@default/'      => '<?php default: ?>',
        ];
        $segment  = preg_replace(array_keys($patterns), array_values($patterns), $segment);
        return sprintf('<?php switch(%s): ?>%s<?php endswitch; ?>', $condition, trim($segment));
    }

    public static function compile($template)
    {
        $stream = self::replace($template);
        if (isset(self::$parent)) {
            $stream       = self::replace(file_get_contents(self::$parent));
            self::$parent = null;
        }
        return $stream;
    }

    protected static function replace($f)
    {
        return preg_replace_callback_array(self::$rules, $f);
    }
}
