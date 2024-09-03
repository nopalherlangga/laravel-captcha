<?php
/**
 * Copyright (c) 2017 - present
 * LaravelCaptcha - TestCase.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 12/9/2018
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

namespace Nopal\Captcha\Tests;

use Nopal\Captcha\CaptchaServiceProvider;
use Nopal\Captcha\Facades\Captcha;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Class TestCase
 * @package Nopal\Captcha\Tests
 */
class TestCase extends OrchestraTestCase
{

	/**
	 * Load package alias
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageAliases($app)
	{

		return [
			'Captcha' => Captcha::class,
		];
	}

	/**
	 * Load package service provider
	 *
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{

		return [CaptchaServiceProvider::class];
	}
}
