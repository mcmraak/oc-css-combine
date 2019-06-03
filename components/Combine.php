<?php namespace Zen\Combine\Components;

use Cms\Classes\ComponentBase;
use Zen\Combine\Controllers\Combine as CombineController;
use Zen\Combine\Models\Settings;
use Request;

class Combine extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CSS Combine',
            'description' => 'Concatenate css files'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $combine = Settings::get('combine');
        $css_update = Settings::get('css_update');

        if($combine){
            $combine = CombineController::buildCombine($css_update);
            $this->page['css_list'] = [$combine['theme_path'] .'assets/css/combine.css'];
        } else {
            $combine = CombineController::buildCombine($css_update);
            $domain = $this->getDomain();

            $clean_list = [];
            foreach ($combine['css_list'] as $item){
                if(preg_match('/^\/(.*)/', $item)) {
                    $clean_list[] = $domain.trim($item);
                } else {
                    $clean_list[] = $domain.$combine['theme_path'] . trim($item);
                }
            }
            $this->page['css_list'] = $clean_list;
        }
    }

    public function getDomain ()
    {
        if (Request::secure())
        {
            return 'https://'.$_SERVER['HTTP_HOST'];
        } else {
            return 'http://'.$_SERVER['HTTP_HOST'];
        }
    }
}
