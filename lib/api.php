<?php

if(!function_exists('ske_klaro'))
{
    /**
     * @param bool $check_only
     * @return array|mixed
     */
    function ske_klaro($check_only = false)
    {
        if((bool)$check_only) {
            return isset($_COOKIE['klaro']);
        }else{
            return (isset($_COOKIE['klaro'])) ? json_decode($_COOKIE['klaro'], true) : [];
        }
    }
}

if(!function_exists('ske_embed'))
{
    function ske_embed($tag, array $attrs)
    {
        return (new \Setcooki\Klaro\Embed\Embed($tag, $attrs))->render();
    }
}