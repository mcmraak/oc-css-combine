<?php namespace Zen\Combine\Controllers;

use Cms\Classes\Theme;

class Combine
{
    public static function buildCombine($css_update)
    {
        $theme = Theme::getActiveTheme();
        $theme_path = base_path('themes/' . $theme->getDirName());

        if($css_update){
            $css_combain = $theme_path.'/assets/css/combine.css';
            $css_files = file($theme_path.'/meta/css.txt');
            $combine = '';

            $path_list = [];
            foreach ($css_files as $css){
                if(preg_match('/^#.*/', $css)) continue;
                if(preg_match('/^\/(.*)/', $css,$match)) {
                    $css_file_path = base_path(trim($match[1]));
                } else {
                    $css_file_path = $theme_path.'/'.trim($css);
                }
                $path_list[$css_file_path]=null;
            }

            foreach($path_list as $css=>$null)
            {
                if(file_exists($css)) {
                    $combine .= file_get_contents($css);
                } else {
                    echo "<div style='text-align:center;color:#fff;background:red'>No such file $css</div>";
                }
            }

            $combine = self::minify($combine);

            file_put_contents($css_combain, $combine);

            return [
                'css_list' => $css_files,
                'theme_path' => '/themes/' . $theme->getDirName() . '/',
            ];
        } else {
            return [
                'css_list' => ['assets/css/combine.css'],
                'theme_path' => '/themes/' . $theme->getDirName() . '/',
            ];
        }
    }

    public static function minify($css)
    {
        if(!$css) return;
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
        }
        $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
        $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
        $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
        $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
        $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
        $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
        $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
        $css = preg_replace('/\p{Zs}+/ims',' ', $css);
        $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
        for ($i=0; $i < count($hit[1]); $i++) {
            $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
        }
        return $css;
    }
}
