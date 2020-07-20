<?php

require_once dirname(__FILE__) . '/api.php';

if(function_exists('add_action'))
{
    add_action('init', function()
    {
        add_filter('the_content', function($content)
        {
            $dom = new \DOMDocument();
            $content = preg_replace_callback('#(?:<(iframe|embed)([^>]*))(?:(?:\/>)|(?:>.*?<\/(?:iframe|embed)>))#i', function($m) use ($dom)
            {
                $attrs = [];
                $dom->loadHTML($m[0]);
                foreach ($dom->getElementsByTagName($m[1]) as $tag)
                {
                    foreach($tag->attributes as $name => $value)
                    {
                        $attrs[$name] = $tag->getAttribute($name);
                    }
                }
                return ske_embed($m[1], $attrs);
            }, $content);
            return $content;
        }, 9999);
    });
}