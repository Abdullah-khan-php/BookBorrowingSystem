<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\BookRepositoryInterface;
use App\Repositories\BookRepository;
use App\Interfaces\ProfileRepositoryInterface;
use App\Repositories\ProfileRepository;
use App\Interfaces\Auth\RegisteredUserRepositoryInterface;
use App\Repositories\Auth\RegisteredUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
        $this->app->bind(RegisteredUserRepositoryInterface::class, RegisteredUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
