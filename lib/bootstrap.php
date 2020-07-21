<?php

require_once dirname(__FILE__) . '/api.php';

//currently only in wordpress environment supported
if(function_exists('add_action'))
{
    add_action('init', function()
    {
        $config = null;
        if(get_stylesheet_directory())
        {
            $config = get_stylesheet_directory() . '/klaroConfigEmbed.json';
            if(is_file($config))
            {
                $config = json_decode(file_get_contents($config));
            }
        }
        add_action('wp_enqueue_scripts', function() use ($config)
        {
            wp_register_style('ske', false);
            wp_enqueue_style('ske');
            wp_add_inline_style('ske', \Setcooki\Klaro\Embed\Embed::style());
            wp_register_script('ske', false);
            wp_enqueue_script('ske');
            wp_add_inline_script('ske', \Setcooki\Klaro\Embed\Embed::script());
            if($config && is_object($config))
            {
                wp_register_script('ske-config', false);
                wp_enqueue_script('ske-config');
                wp_add_inline_script('ske-config', 'var klaroConfigEmbed = '.json_encode($config).';');
            }
        }, 9999);

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
