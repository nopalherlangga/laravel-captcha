<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - recaptcha.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

/**
 * To configure correctly please visit https://developers.google.com/recaptcha/docs/start
 */
return [

    /**
     * Captcha default version
     * Supported: "recaptcha_v2", "trunstile"
     */
    'version' => env('CAPTCHA_VERSION', 'recaptcha_v2'),

    'recaptcha' => [
        
        /**
         * The site key
         * get site key @ www.google.com/recaptcha/admin
         */
        'site_key' => env('RECAPTCHA_SITE_KEY', env('CAPTCHA_SITE_KEY', '')),
    
        /**
         * The secret key
         * get secret key @ www.google.com/recaptcha/admin
         */
        'secret_key' => env('RECAPTCHA_SECRET_KEY', env('CAPTCHA_SECRET_KEY', '')),

    ],

    'turnstile' => [
        
        /**
         * The site key
         * get site key @ dash.cloudflare.com/XXXXX/turnstile
         */
        'site_key' => env('TURNSTILE_SITE_KEY', env('CAPTCHA_SITE_KEY', '')),
    
        /**
         * The secret key
         * get secret key @ dash.cloudflare.com/XXXXX/turnstile
         */
        'secret_key' => env('TURNSTILE_SECRET_KEY', env('CAPTCHA_SECRET_KEY', '')),

    ],


    /**
     *
     * The curl timout in seconds to validate a recaptcha token
     * @since v3.5.0
     *
     */
    'curl_timeout'                 => 10,

    /**
     *
     * IP addresses for which validation will be skipped
     * IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
     *
     */
    'skip_ip'                      => env('RECAPTCHA_SKIP_IP', []),

    /**
     *
     * The name of the parameter used to send Google reCAPTCHA token to verify route
     * @since v3.2.0
     *
     */
    'default_token_parameter_name' => 'token',

    /**
     *
     * The default Google reCAPTCHA language code
     * It has no effect with v3
     * @see   https://developers.google.com/recaptcha/docs/language
     * @since v3.6.0
     *
     */
    'default_language'             => null,

    /**
     *
     * Deferring the render can be achieved by specifying your onload callback function and adding parameters to the JavaScript resource.
     * It has no effect with v3 and invisible
     * @see   https://developers.google.com/recaptcha/docs/display#explicit_render
     * @since v4.0.0
     * Supported true, false
     *
     */
    'explicit'                     => false,

    /**
     *
     * Set `true` when the error message must be null
     * @since v5.1.0
     * Default false
     *
     */
    'empty_message' => false,

    /**
     *
     * Set either the error message or the errom message translation key
     * @since v5.1.0
     * Default 'validation.recaptcha'
     *
     */
    'error_message_key' => 'validation.recaptcha',

    /**
     *
     * g-recaptcha tag attributes and grecaptcha.render parameters (v2 only)
     * @see   https://developers.google.com/recaptcha/docs/display#render_param
     * @since v4.0.0
     */
    'tag_attributes'               => [

        /**
         * The color theme of the widget.
         * Supported "light", "dark"
         */
        'theme'            => 'light',

        /**
         * The size of the widget.
         * Supported "normal", "compact"
         */
        'size'             => 'normal',

        /**
         * The tabindex of the widget and challenge.
         * If other elements in your page use tabindex, it should be set to make user navigation easier.
         */
        'tabindex'         => 0,

        /**
         * The name of your callback function, executed when the user submits a successful response.
         * The g-recaptcha-response token is passed to your callback.
         * DO NOT SET "biscolabOnloadCallback"
         */
        'callback'         => null,

        /**
         * The name of your callback function, executed when the reCAPTCHA response expires and the user needs to re-verify.
         * DO NOT SET "biscolabOnloadCallback"
         */
        'expired-callback' => null,

        /**
         * The name of your callback function, executed when reCAPTCHA encounters an error (usually network connectivity) and cannot continue until connectivity is restored.
         * If you specify a function here, you are responsible for informing the user that they should retry.
         * DO NOT SET "biscolabOnloadCallback"
         */
        'error-callback'   => null,
    ]
];
