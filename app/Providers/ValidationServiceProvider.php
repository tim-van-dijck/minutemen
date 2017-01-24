<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

class ValidationServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Tell Laravel to use our custom created validator class. This class will extend the
		// normal validation class, so you can add methods and override methods.

		$this->app->validator->resolver(function ($translator, $data, $rules, $messages) {

			// We create our own validation class here, we will create that after this
			return new CustomValidation($translator, $data, $rules, $messages);
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}

class CustomValidation extends Validator
{
	public function validateProfanityFilter($attribute, $value, $parameters)
	{
		$profanity = 'anal|anus|arse|ass|ballsack|balls|bastard|blowjob|blow job|bollock|bollok|boner|boob|bugger|butt|buttplug|clitoris|cock|coon|cunt|dick|dildo|dyke|fag|feck|fellate|fellatio|felching|fuck|f u c k|fudgepacker|fudge packer|flange|Goddamn|God damn|hell|homo|jerk|jizz|knobend|knob end|labia|muff|nigger|nigga|penis|piss|poop|prick|pussy|queer|scrotum|sex|shit|s hit|sh1t|slut|smegma|spunk|tit|tosser|turd|twat|vagina|wank|whore';
				
		return !preg_match('/[^!@#$%^&*]*('.$profanity.')[^!@#$%^&*]*/i', $value);
	}
	public function validateMultipleOfTwo($attribute, $value, $parameters)
	{
        while($value > 2){ $value /= 2; }
        if ($value == 2) { return true; }
        else { return false; }
	}
}