<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;


class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {

        //
        
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer(

            'layouts.navbar',

            function ($view) {


                if (\Illuminate\Support\Facades\Auth::check()) {


    $userId = \Illuminate\Support\Facades\Auth::id();



    $notifikasi = Notifikasi::where(
        'user_id',
        $userId
    )
    ->latest()
    ->take(5)
    ->get();



    $jumlahNotifikasi = Notifikasi::where(
        'user_id',
        $userId
    )
    ->where(
        'dibaca',
        false
    )
    ->count();






                    $view->with([

                        'notifikasi' => $notifikasi,

                        'jumlahNotifikasi' => $jumlahNotifikasi

                    ]);


                }


            }

        );

    }

}