<?php namespace Zen\Combine\Models;

use Config;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation as ValidationTrait;
use Cms\Classes\Theme;

class Settings extends Model
{
    use ValidationTrait;

    public $theme_path;

    public function __construct()
    {
        parent::__construct();
        $theme = Theme::getActiveTheme();
        $theme_path = base_path(). '/themes/' . $theme->getDirName();

        if(!file_exists($theme_path.'/assets')){
            mkdir($theme_path.'/assets');
        }
        if(!file_exists($theme_path.'/assets/css')){
            mkdir($theme_path.'/assets/css');
        }
        if(!file_exists($theme_path.'/meta')){
            mkdir($theme_path.'/meta');
        }
        if(!file_exists($theme_path.'/meta/css.txt')){
            file_put_contents($theme_path.'/meta/css.txt','');
        }
        $this->theme_path = $theme_path;
    }

    public $implement = [
        'System.Behaviors.SettingsModel',
    ];

    public $settingsCode = 'zen_combine_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
    ];

    public function getCssListAttribute()
    {
        return file_get_contents($this->theme_path.'/meta/css.txt');
    }

    public function setCssListAttribute($value)
    {
        return file_put_contents($this->theme_path.'/meta/css.txt', $value);
    }

}
