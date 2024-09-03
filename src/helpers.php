<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - helpers.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

if (!function_exists('captcha')) {
    /**
     * @return Nopal\ReCaptcha\Captcha
     */
    function captcha(): \Nopal\Captcha\Captcha
    {

        return app('captcha');
    }
}