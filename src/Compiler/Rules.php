<?php

namespace Max\View\Compiler;

class Rules
{
    public const RULES = [
        '/\{\{\$(.*)\}\}/'                          => '<?php echo $\\1; ?>',
        '/\{\{(.*)\|(.*)\}\}/'                      => '<?php echo \\2(\\1); ?>',
        '/\{\{(.*)\}\}/'                            => '<?php echo \\1; ?>',
        '/@include\([\'"](.*)[\'"]\)/'              => '<?php include(\'\\1\' . config(\'view.max.options.suffix\')); ?>',
        '/@foreach\((.*)\)/'                        => '<?php foreach(\\1): ?>',
        '/@endforeach/'                             => '<?php endforeach; ?>',
        '/@for\((.*)\)/'                            => '<?php for (\\1): ?>',
        '/@endfor/'                                 => '<?php endfor; ?>',
        '/@if\((.*)\)/'                             => '<?php if (\\1): ?>',
        '/@elseif\((.*)\)/'                         => '<?php elseif (\\1): ?>',
        '/@else/'                                   => '<?php else: ?>',
        '/(@endif|@endunless|@endempty|@endisset)/' => '<?php endif; ?>',
        '/@unless\((.*)\)/'                         => '<?php if (!(\\1)): ?>',
        '/@isset\((.*)\)/'                          => '<?php if (isset(\\1)): ?>',
        '/@empty\((.*)\)/'                          => '<?php if (empty(\\1)): ?>',
        '/@switch\((.*)\)/'                         => '<?php switch (\\1): ?>',
        '/@case\((.*)\)/'                           => '<?php case (\\1): ?>',
        '/@default/'                                => '<?php default: ?>',
        '/@endswitch/'                              => '<?php endswitch ?>',
    ];
}
