<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-10-08
 * Time: 13:15
 */

namespace App\Providers;


use GeoIp2\Database\Reader;
use Illuminate\Support\ServiceProvider;

class GeoIp2ServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Reader::class, function ($app) {
            return new Reader(storage_path("GeoLite2-City.mmdb"));
        });
    }

    public function provides()
    {
        return [Reader::class];
    }
}