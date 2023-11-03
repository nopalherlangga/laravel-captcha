<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - helpers.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

use Nopal\Captcha\Facades\Captcha;

if (!function_exists('captcha')) {
    /**
     * @return Nopal\ReCaptcha\Captcha
     */
    function captcha(): \Nopal\Captcha\Captcha
    {

        return app('captcha');
    }
}

/**
 * call ReCaptcha::htmlFormButton()
 * Write HTML <button> tag in your HTML code
 * Insert before </form> tag
 *
 * Warning! Using only with ReCAPTCHA INVISIBLE
 *
 * @param $buttonInnerHTML What you want to write on the submit button
 */
if (!function_exists('htmlFormButton')) {

    /**
     * @param null|string $button_label
     * @param array|null  $properties
     *
     * @return string
     */
    function htmlFormButton(?string $button_label = 'Submit', ?array $properties = []): string
    {

        return Captcha::htmlFormButton($button_label, $properties);
    }
}

/**
 * call ReCaptcha::getFormId()
 * return the form ID
 * Warning! Using only with ReCAPTCHA invisible
 */
if (!function_exists('getFormId')) {

    /**
     * @return string
     */
    function getFormId(): string
    {

        return Captcha::getFormId();
    }
}

/**
 * return ReCaptchaBuilder::DEFAULT_RECAPTCHA_FIELD_NAME value "g-recaptcha-response"
 * Use V2 (checkbox and invisible)
 */
if (!function_exists('recaptchaFieldName')) {

    /**
     * @return string
     */
    function recaptchaFieldName(): string
    {

        return \Biscolab\Captcha\ReCaptcha\ReCaptchaBuilder::DEFAULT_RECAPTCHA_FIELD_NAME;
    }
}
