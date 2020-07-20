<?php

namespace Setcooki\Klaro\Embed;

/**
 * Class Embed
 * @package Setcooki\Klaro\Embed
 */
class Embed
{
    private static $instances = 0;

    /**
     * @var null
     */
    protected $tag = null;

    /**
     * @var array
     */
    protected $attrs = [];


    /**
     * Embed constructor.
     * @param $tag
     * @param array $attrs
     */
    public function __construct($tag, array $attrs = [])
    {
        $this->tag = $tag;
        $this->attrs = implode(' ', array_map(function($v, $k)
        {
            return sprintf('%s="%s"', $k, $v);
        }, $attrs, array_keys($attrs)));
    }


    /**
     * @return false|string
     */
    public function render()
    {
        ob_start();
        if(static::$instances === 0) { ?>
            <style type="text/css">.test {}</style>
        <?php } ?>
        <div>

        </div>
        <script type="text/template">
            <?php echo base64_encode(sprintf('<%s %s></%s>', $this->tag, $this->attrs, $this->tag)); ?>
        </script><?php

        static::$instances++;
        return ob_get_clean();
    }
}