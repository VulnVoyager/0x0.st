<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Создаем директорию для загрузок если её нет
        if (!Storage::exists('uploads')) {
            Storage::makeDirectory('uploads');
        }
    }
}