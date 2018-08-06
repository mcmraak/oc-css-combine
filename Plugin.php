<?php namespace Zen\Combine;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Zen\Combine\Components\Combine' => 'combine',
        ];
    }

    public function registerSettings()
    {
        return [
            'options' => [
                'label'       => 'CSS Combine',
                'description' => 'Combine css files',
                'icon'        => 'icon-rocket',
                'permissions' => ['zen.combine.open'],
                'class' => 'Zen\Combine\Models\Settings',
                'order' => 600,
            ]
        ];
    }
}
