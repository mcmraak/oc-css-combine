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
            foreach($css_files as $css)
            {
                $combain .= self::minify(file_get_contents($theme_path.'/'.trim($css)));
            }
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
