<?php
/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - ReCaptchaLangTest.php
 * author: Roberto Belotti - roby.belotti@gmail.com
 * web : robertobelotti.com, github.com/biscolab
 * Initial version created on: 7/8/2019
 * MIT license: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

namespace Nopal\Captcha\Tests;

use Nopal\Captcha\Facades\Captcha;

class ReCaptchaLangTest extends TestCase
{
	/**
	 * @tests
	 */
	public function testHtmlScriptTagJsApiGetHtmlScriptWithHlParam()
	{

		$r = Captcha::renderScriptTag();
		$this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?hl=it" async defer></script>', $r);
	}

	/**
	 * @tests
	 */
	public function testHtmlScriptTagJsApiGetHtmlScriptOverridingHlParam()
	{

		$r = Captcha::renderScriptTag(['lang' => 'en']);
		$this->assertEquals('<script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>', $r);
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application $app
	 *
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{

		$app['config']->set('captcha.default_language', 'it');
	}
}