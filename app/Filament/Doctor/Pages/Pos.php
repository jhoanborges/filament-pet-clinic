<?php

namespace App\Filament\Doctor\Pages;

use Filament\Pages\Page;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Foundation\Vite;

class Pos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    //protected static ?string $title = 'Calendar';
    //protected static string $view = 'filament.doctor.pages.doctor-calendar';
    //protected static ?string $navigationGroup = 'Citas';


    protected static string $view = 'filament.pages.demo-with-react';

    public function mount()
    {
        if (app()->environment('local')) {
            FilamentView::registerRenderHook(
                name: PanelsRenderHook::HEAD_START,
                hook: fn() => app(Vite::class)->reactRefresh(),
            );
        }
    }
}
