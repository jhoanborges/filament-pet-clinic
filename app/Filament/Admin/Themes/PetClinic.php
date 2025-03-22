<?php

namespace App\Filament\Admin\Themes;

use Filament\Panel;
use Hasnayeen\Themes\Contracts\CanModifyPanelConfig;
use Hasnayeen\Themes\Contracts\Theme;

class PetClinic implements CanModifyPanelConfig, Theme
{
    public static function getName(): string
    {
        return 'pet-clinic';
    }

    public static function getPath(): string
    {
        return 'resources/css/filament/admin/themes/pet-clinic.css';
    }

    public function getThemeColor(): array
    {
        return [
            'primary' => '#9056a3',
            'secondary' => '#c978b2',
            'danger' => '#e11d48',
            'gray' => '#6b7280',
            'info' => '#3b82f6',
            'success' => '#10b981',
            'warning' => '#f97316',
            'pink' => '#c978b2',
            'purple' => '#9056a3',
            'dark-blue' => '#3a419a',
            'light-blue' => '#e3f3f7',
            'dark-gray' => '#333333',
        ];
    }

    public function modifyPanelConfig(Panel $panel): Panel
    {
        return $panel
            ->viteTheme($this->getPath());
    }
}
