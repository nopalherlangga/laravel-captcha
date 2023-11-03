<?php

/**
 * Copyright (c) 2023 - present
 * LaravelCaptcha - Captcha.php
 * author: Roberto Belotti - roby.belotti@gmail.com, Nopal Herlangga - 49229021+nopalherlangga@users.noreply.github.com
 * web : robertobelotti.com, github.com/biscolab, github.com/nopalherlangga
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/nopalherlangga/laravel-captcha/blob/master/LICENSE
 */

namespace Nopal\Captcha;

use Illuminate\Support\Arr;
use Nopal\Captcha\Exceptions\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Class Captcha
 * @package Nopal\Captcha
 */
class Captcha
{

    /**
     * @var string
     */
    const DEFAULT_API_VERSION = 'recaptcha_v2';

    /**
     * @var int
     */
    const DEFAULT_CURL_TIMEOUT = 10;

    /**
     * @var string
     */
    const DEFAULT_ONLOAD_JS_FUNCTION = 'biscolabOnloadCallback';

    /**
     * @var string
     */
    const DEFAULT_RECAPTCHA_FIELD_NAME = 'g-recaptcha-response';

    /**
     * The Site key
     * @var string
     */
    protected $api_site_key;
    /**
     * The Secret key
     * @var string
     */
    protected $api_secret_key;

    /**
     * Whether is true the ReCAPTCHA is inactive
     * @var boolean
     */
    protected $skip_by_ip = false;

    /**
     * The API request URI
     * @var string
     */
    protected $api_url = '';

    /**
     * The URI of the API Javascript file to embed in you pages
     * @var string
     */
    protected $api_js_url = '';

    /**
     * The chosen Captcha version
     * @var string
     */
    protected $version;

    protected static $allowed_data_attribute = [
        "theme",
        "size",
        "tabindex",
        "callback",
        "expired-callback",
        "error-callback",
    ];

    /**
     * Captcha constructor.
     *
     * @param string $version
     */
    public function __construct($version)
    {
        $this->setVersion($version);
        $this->setApiSiteKey();
        $this->setApiSecretKey();
        $this->setSkipByIp($this->skipByIp());
        $this->setApiUrls();
    }


    /**
     * @return int
     */
    public function getCurlTimeout(): int
    {

        return config('captcha.curl_timeout', self::DEFAULT_CURL_TIMEOUT);
    }

    /**
     * @param string $version
     *
     * @return Captcha
     */
    public function setVersion(string $version): Captcha
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param bool $skip_by_ip
     *
     * @return Captcha
     */
    public function setSkipByIp(bool $skip_by_ip): Captcha
    {

        $this->skip_by_ip = $skip_by_ip;

        return $this;
    }

    /**
     * @param string $api_site_key
     *
     * @return Captcha
     */
    public function setApiSiteKey(): Captcha
    {
        switch($this->version)
        {
            case 'recaptcha_v2':
                $this->api_site_key = config('captcha.recaptcha.site_key');
                break;
            case 'turnstile':
                $this->api_site_key = config('captcha.turnstile.site_key');
                break;
        }
        return $this;
    }
    /**
     * @param string $api_secret_key
     *
     * @return Captcha
     */
    public function setApiSecretKey(): Captcha
    {
        switch($this->version)
        {
            case 'recaptcha_v2':
                $this->api_secret_key = config('captcha.recaptcha.secret_key');
                break;
            case 'turnstile':
                $this->api_secret_key = config('captcha.turnstile.secret_key');
                break;
        }
        return $this;
    }

    /**
     * @return Captcha
     */
    public function setApiUrls(): Captcha
    {
        switch($this->version)
        {
            case 'recaptcha_v2':
                $this->api_url = 'https://www.google.com/recaptcha/api/siteverify';
                $this->api_js_url = 'https://www.google.com/recaptcha/api.js';
                break;
            case 'turnstile':
                $this->api_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
                $this->api_js_url = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
                break;
        }
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getIpWhitelist()
    {

        $whitelist = config('captcha.skip_ip', []);

        if (!is_array($whitelist)) {
            $whitelist = explode(',', $whitelist);
        }

        $whitelist = array_map(function ($item) {

            return trim($item);
        }, $whitelist);

        return $whitelist;
    }

    /**
     * Checks whether the user IP address is among IPs "to be skipped"
     *
     * @return boolean
     */
    public function skipByIp(): bool
    {
        return IpUtils::checkIp(request()->getClientIp(), $this->getIpWhitelist());
    }

    /**
     * Write script HTML tag in you HTML code
     * Insert before </head> tag
     *
     * @param string $version
     * @param array|null $configuration
     *
     * @return string
     * @throws \Exception
     */
    public function renderScriptTag(?array $configuration = []): string
    {

        $query = [];
        $html = '';

        if($this->version == 'turnstile') {
            Arr::set($query, 'compat', 'recaptcha');
        }

        // Language: "hl" parameter
        // resources $configuration parameter overrides default language
        $language = Arr::get($configuration, 'lang');
        if (!$language) {
            $language = config('captcha.default_language', null);
        }
        if ($language) {
            Arr::set($query, 'hl', $language);
        }

        // Onload JS callback function: "onload" parameter
        // "render" parameter set to "explicit"
        if (config('captcha.explicit', null) && $this->version == 'recaptcha_v2') {
            Arr::set($query, 'render', 'explicit');
            Arr::set($query, 'onload', self::DEFAULT_ONLOAD_JS_FUNCTION);

            /** @scrutinizer ignore-call */
            $html = $this->getOnLoadCallback();
        }

        // Create query string
        $query = ($query) ? '?' . http_build_query($query) : "";
        $html .= "<script src=\"" . $this->api_js_url .  $query . "\" async defer></script>";

        return $html;
    }

    /**
     * Write Captcha HTML tag in your FORM
     * Insert before </form> tag
     * 
     * @param null|array $attributes
     * @return string
     */
    public function render(?array $attributes = []): string
    {

        $data_attributes = [];
        $config_data_attributes = array_merge($this->getTagAttributes(), self::cleanAttributes($attributes));
        ksort($config_data_attributes);
        foreach ($config_data_attributes as $k => $v) {
            if ($v) {
                $data_attributes[] = 'data-' . $k . '="' . $v . '"';
            }
        }

        $html = '<div class="g-recaptcha" ' . implode(" ", $data_attributes) . ' id="recaptcha-element"></div>';

        return $html;
    }

    /**
     * Call out to reCAPTCHA and process the response
     *
     * @param string $response
     *
     * @return boolean|array
     */
    public function validate($response)
    {

        if ($this->skip_by_ip) {
            if ($this->version == 'recaptcha_v3') {
                // Add 'skip_by_ip' field to response
                return [
                    'skip_by_ip' => true,
                    'score'      => 0.9,
                    'success'    => true
                ];
            }

            return true;
        }

        $params = http_build_query([
            'secret'   => $this->api_secret_key,
            'remoteip' => request()->getClientIp(),
            'response' => $response,
        ]);

        $url = $this->api_url;

        if (function_exists('curl_version')) {

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->getCurlTimeout());
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $curl_response = curl_exec($curl);
        } else {
            $curl_response = file_get_contents($url);
        }

        if (is_null($curl_response) || empty($curl_response)) {
            if ($this->version == 'recaptcha_v3') {
                // Add 'error' field to response
                return [
                    'error'   => 'cURL response empty',
                    'score'   => 0.1,
                    'success' => false
                ];
            }

            return false;
        }
        $response = json_decode(trim($curl_response), true);

        if ($this->version == 'recaptcha_v3') {
            return $response;
        }

        return $response['success'];
    }

    /**
     * @return string
     */
    public function getApiSiteKey(): string
    {
        return $this->api_secret_key;
    }

    /**
     * @return string
     */
    public function getApiSecretKey(): string
    {

        return $this->api_secret_key;
    }

    /**
     * @return string
     */
    public function getOnLoadCallback(): string
    {

        return "";
    }

    /**
     * @return array
     * @throws InvalidConfigurationException
     */
    public function getTagAttributes(): array
    {

        $tag_attributes = [
            'sitekey' => $this->api_site_key
        ];

        $tag_attributes = array_merge($tag_attributes, config('recaptcha.tag_attributes', []));

        if (Arr::get($tag_attributes, 'callback') === Captcha::DEFAULT_ONLOAD_JS_FUNCTION) {
            throw new InvalidConfigurationException('Property "callback" ("data-callback") must be different from "' . Captcha::DEFAULT_ONLOAD_JS_FUNCTION . '"');
        }

        if (Arr::get($tag_attributes, 'expired-callback') === Captcha::DEFAULT_ONLOAD_JS_FUNCTION) {
            throw new InvalidConfigurationException('Property "expired-callback" ("data-expired-callback") must be different from "' . Captcha::DEFAULT_ONLOAD_JS_FUNCTION . '"');
        }

        if (Arr::get($tag_attributes, 'error-callback') === Captcha::DEFAULT_ONLOAD_JS_FUNCTION) {
            throw new InvalidConfigurationException('Property "error-callback" ("data-error-callback") must be different from "' . Captcha::DEFAULT_ONLOAD_JS_FUNCTION . '"');
        }

        return $tag_attributes;
    }

    /**
     * Compare given attributes with allowed attributes
     *
     * @param array|null $attributes
     * @return array
     */
    public static function cleanAttributes(?array $attributes = []): array
    {
        return array_filter($attributes, function ($k) {
            return in_array($k, self::$allowed_data_attribute);
        }, ARRAY_FILTER_USE_KEY);
    }
}
