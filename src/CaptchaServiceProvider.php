<?php

/**
 * Copyright (c) 2017 - present
 * LaravelCaptcha - CaptchaServiceProvider.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

namespace Nopal\Captcha;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Class CaptchaServiceProvider
 * @package Nopal\Captcha
 */
class CaptchaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function boot()
    {

        $this->addValidationRule();
        $this->registerRoutes();
        $this->publishes([
            __DIR__ . '/../config/captcha.php' => config_path('captcha.php'),
        ], 'config');
    }

    /**
     * Extends Validator to include a recaptcha type
     */
    public function addValidationRule()
    {
        $message = null;

        if (!config('captcha.empty_message')) {
            $message = trans(config('captcha.error_message_key'));
        }
        Validator::extendImplicit('captcha', function ($attribute, $value) {
            return app('captcha')->validate($value);
        }, $message);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/captcha.php',
            'captcha'
        );

        $this->registerCaptchaBuilder();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {

        return ['captcha'];
    }

    /**
     * @return CaptchaServiceProvider
     *
     * @since v3.4.1
     */
    protected function registerRoutes(): CaptchaServiceProvider
    {

        Route::get(
            config('captcha.default_validation_route', 'biscolab-recaptcha/validate'),
            ['uses' => 'Biscolab\Captcha\Controllers\ReCaptchaController@validateV3']
        )->middleware('web');

        return $this;
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerCaptchaBuilder()
    {

        $this->app->singleton('captcha', function ($app) {
            return new Captcha(config('captcha.version'));
        });
    }
}
