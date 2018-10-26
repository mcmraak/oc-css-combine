<?php namespace Zen\Combine\Controllers;

use Cms\Classes\Theme;

class Combine
{
    public static function buildCombine($css_update)
    {
        $theme = Theme::getActiveTheme();
        $theme_path = base_path(). '/themes/' . $theme->getDirName();

        if($css_update){
            $css_combain = $theme_path.'/assets/css/combine.css';
            $css_files = file($theme_path.'/meta/css.txt');
            $combain = '';

            $path_list = [];
            foreach ($css_files as $css){
                if(preg_match('/^#.*/', $css)) continue;
                $css_file_path = $theme_path.'/'.trim($css);
                $path_list[$css_file_path]=null;
            }

            foreach($path_list as $css=>$null)
            {
                if(file_exists($css)) {
                    $combain .= file_get_contents($css);
                } else {
                    echo "<div style='text-align:center;color:#fff;background:red'>No such file $css</div>";
                }
            }

            $combain = self::minify($combain);

            file_put_contents($css_combain, $combain);
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
        return str_replace('; ',';',
               str_replace(' }','}',
               str_replace('{ ','{',
               str_replace([
                   "\r\n",
                   "\r",
                   "\n",
                   "\t",
                   '  ',
                   '    ',
                   '    '
               ], "", preg_replace(
                   '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
                   '', $css)))));
    }
}
