<?php

namespace App\Helpers;

use Hasnayeen\Themes\Themes;
use App\Filament\Admin\Themes\PetClinic;

class ThemeHelper
{
    public static function getThemeColors()
    {
        $themes = app(Themes::class);
        $theme = $themes->getCurrentTheme();
        
        if (!$theme) {
            $theme = new PetClinic();
        }

        return $theme->getThemeColor();
    }

    public static function getPrimaryColor()
    {
        return self::getThemeColors()['primary'] ?? '#9056a3';
    }

    public static function getSecondaryColor()
    {
        return self::getThemeColors()['secondary'] ?? '#c978b2';
    }
} 