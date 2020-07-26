<?php

namespace Setcooki\Klaro\Embed;

use MatthiasMullie\Minify;

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
     * @var null
     */
    protected $config = null;

    /**
     * @var null
     */
    protected $klaro = null;

    /**
     * @var array
     */
    protected $attrs = [];

    /**
     * @var null
     */
    protected $src = null;

    /**
     * @var null
     */
    protected $provider = null;


    /**
     * Embed constructor.
     * @param $tag
     * @param array $attrs
     * @param null $config
     */
    public function __construct($tag, array $attrs = [], $config = null)
    {
        $this->klaro = ske_klaro();
        $this->tag = $tag;
        $this->attrs = $attrs;
        $this->config = $config;
        $this->src = (array_key_exists('src', $attrs)) ? $attrs['src'] : null;
        $this->provider = $this->provider($attrs);
    }


    /**
     * @return false|string
     */
    public static function style()
    {
        $svg = file_get_contents(dirname(__FILE__) . '/../assets/embed.svg');
        $svg = preg_replace('=.*(\<svg(?:[^>].*)\>.*\<\/svg\>).*=ism', '$1', $svg);
        $svg = str_replace("\n", '', $svg);
        $svg = preg_replace('=\s+=i', ' ', $svg);
        ob_start(); ?>
        <style type="text/css">
        .ske-embed {
            position: relative;
            display: block;
            overflow: hidden;
            max-width: 100%;
            min-width: 240px;
            height: auto;
            padding-bottom: inherit;
            background: transparent url("data:image/svg+xml;base64,<?php echo base64_encode($svg); ?>") center center/cover no-repeat;
        }

        .ske-embed-notice {
            top: 50%;
            left: 0;
            position: absolute;
            width: 100%;
            text-align: center;
            transform: translateY(-50%);
            background: hsla(0, 0%, 0%, 0.8);
            color: #fff;
            font-size: 13px;
            padding: 10px;
        }

        .ske-embed-notice a {
            color: #fff !important;
            text-decoration: underline;
        }

        .ske-embed-caption {
            overflow-wrap: break-word;
            word-wrap: break-word;
            -ms-word-break: break-all;
            word-break: break-all;
            word-break: break-word;
            -ms-hyphens: auto;
            -moz-hyphens: auto;
            -webkit-hyphens: auto;
            hyphens: auto;
        }

        .ske-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: inherit;
            padding: 0.5em 1em;
            text-decoration: none;
            background: #ff0000;
            border-radius: 20px;
            color: #fff;
            text-decoration: none !important;
        }
        </style><?php
        return (new Minify\CSS())->add(ob_get_clean())->minify();
    }


    /**
     * @return false|string
     */
    public static function script()
    {
        ob_start(); ?>
        <script type="text/javascript">
            var decodeBase64Raw = function (r) {
                var o, f = {}, n = [], e = "", t = String.fromCharCode,
                    a = [[65, 91], [97, 123], [48, 58], [43, 44], [47, 48]];
                for (z in a) for (o = a[z][0]; o < a[z][1]; o++) n.push(t(o));
                for (o = 0; o < 64; o++) f[n[o]] = o;
                for (o = 0; o < r.length; o += 72) {
                    var h, g = 0, i = 0, s = r.substring(o, o + 72);
                    for (h = 0; h < s.length; h++) for (g = (g << 6) + f[s.charAt(h)], i += 6; i >= 8;) e += t((g >>> (i -= 8)) % 256)
                }
                return e
            };
            var decodeBase64Atob = function (r) {
                return decodeURIComponent(atob(r).split('').map(function (c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
            };
            var decodeBase64 = function (s) {
                s = s.trim();
                if (typeof atob === "function") {
                    return decodeBase64Atob(s);
                } else if (typeof Buffer === "function") {
                    return Buffer.from(s, "utf-8").toString("base64");
                } else {
                    return decodeBase64Raw(s);
                }
            };
            (function ($) {
                $(document).ready(function () {
                    var config = null;
                    var cache = [];
                    if ('klaro' in window) {
                        if ('embed' in klaro.getManager().config) {
                            config = klaro.getManager().config.embed;
                        } else if ('klaroConfigEmbed' in window) {
                            config = window.klaroConfigEmbed;
                        }
                        if (config && 'provider' in config) {
                            var e = null;
                            $.each(config.provider, function (i, p) {
                                e = $('.ske-embed[data-provider="' + p.name + '"]');
                                if (e.length) {
                                    if ('titleText' in p) {
                                        e.find('.ske-embed-caption').html(p.titleText);
                                    }
                                    if ('privacyPolicyLink' in p) {
                                        e.find('.ske-embed-more').html('<a href="' + p.privacyPolicyLink + '" target="_blank">' + (('privacyPolicyText' in p) ? p.privacyPolicyText : 'More...') + '</a>')
                                    }
                                    if ('buttonText' in p) {
                                        e.find('.ske-button').text(p.buttonText);
                                    }
                                    if ('hideApp' in p && p.hideApp && 'app' in p) {
                                        var index = -1;
                                        $.each(klaro.getManager().config.apps, function (j, a) {
                                            if (a.name === p.app) index = j;
                                            return true;
                                        });
                                        if (index > -1) {
                                            cache[p.app] = klaro.getManager().config.apps[index];
                                            klaro.getManager().config.apps.splice(index, 1);
                                        }
                                    }
                                }
                            });
                        }
                    }
                    $('.ske-button').on('click', function (e) {
                        var c = $(e.currentTarget).closest('.ske-embed');
                        if (c.length) {
                            var s = null, h = '', p = c.data('provider');
                            if (p) {
                                if (config && 'provider' in config && 'klaro' in window) {
                                    var _p = $.grep(config.provider, function (a) {
                                        return (a.name === p);
                                    });
                                    _p = ('app' in _p[0]) ? _p[0]['app'] : _p[0]['name'];
                                    klaro.getManager().updateConsent(_p, true);
                                    klaro.getManager().saveAndApplyConsents();
                                    if (_p in cache) {
                                        klaro.getManager().config.apps.push(cache[_p]);
                                    }
                                }
                                $.each($('.ske-embed[data-provider="' + p + '"]'), function (i, e) {
                                    s = $(e).next('script[type="text/template"]');
                                    h = decodeBase64(s.html());
                                    s.replaceWith(h);
                                    $(e).remove();
                                })
                            } else {
                                s = c.next('script[type="text/template"]');
                                h = decodeBase64(s.html());
                                s.replaceWith(h);
                                c.remove();
                            }
                        }
                    });
                });
            })(jQuery.noConflict())
        </script><?php
        return (new Minify\Js())->add(ob_get_clean())->minify();
    }


    /**
     * @param array $attrs
     * @return string|null
     */
    protected function provider(array $attrs = [])
    {
        if (array_key_exists('src', $attrs)) {
            if (stripos($attrs['src'], 'vimeo') !== false) {
                return 'vimeo';
            } else if (stripos($attrs['src'], 'youtube') !== false) {
                return 'youtube';
            }
        }
        return null;
    }


    /**
     * @param array $attrs
     * @return string
     */
    protected function attrs(array $attrs = [])
    {
        return implode(' ', array_map(function ($v, $k) {
            return sprintf('%s="%s"', $k, $v);
        }, $attrs, array_keys($attrs)));
    }


    /**
     * @return bool
     */
    protected function consented()
    {
        if (!empty($this->klaro) && !empty($this->provider) && !empty($this->config) && is_object($this->config) && isset($this->config->provider)) {
            foreach ((array)$this->config->provider as $p) {
                if ((string)$p->name === (string)$this->provider) {
                    if (isset($p->app) && array_key_exists($p->app, $this->klaro) && (bool)$this->klaro[$p->app]) {
                        return true;
                    } else if (array_key_exists($p->name, $this->klaro) && (bool)$this->klaro[$p->name]) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    /**
     * @return false|string
     */
    public function render()
    {
        if ($this->consented()) {
            return sprintf('<%s %s></%s', $this->tag, $this->attrs($this->attrs), $this->tag);
        }

        ob_start(); ?>
        <div id="ske-<?php echo(static::$instances + 1); ?>" data-provider="<?php echo (string)$this->provider; ?>"
             data-instance="<?php echo(static::$instances + 1); ?>"
             class="ske-embed">
            <div class="ske-embed-notice">
                <p class="ske-embed-caption">By clicking the following link (<i
                        style="color: #c0c0c0"><?php echo $this->src; ?></i>) you accept the data privacy statement of
                    the corresponding external provider (<?php echo $this->provider; ?>)</p>
                <p class="ske-embed-more"></p>
                <p class="ske-embed-action"><a class="ske-button" href="javascript:void(0);">Click here</a></p>
            </div>
        </div>
        <script type="text/template">
            <?php echo base64_encode(sprintf('<%s %s></%s>', $this->tag, $this->attrs($this->attrs), $this->tag)); ?>
        </script>
        <?php

        static::$instances++;
        return ob_get_clean();
    }
}