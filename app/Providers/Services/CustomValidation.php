<?php

namespace App\Providers\Services\Validation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

class CustomValidation extends Validator
{
	public function validateProfanityFilter($attribute, $value, $parameters)
	{
		$profanity_f = 'anal|anus|arse|ass|ballsack|balls|bastard|blowjob|blow job|bollock|bollok|boner|boob|bugger|butt|buttplug|clitoris|cock|coon|cunt|dick|dildo|dyke|fag|feck|fellate|fellatio|felching|fuck|f u c k|fudgepacker|fudge packer|flange|Goddamn|God damn|hell|homo|jerk|jizz|knobend|knob end|labia|muff|nigger|nigga|penis|piss|poop|prick|pussy|queer|scrotum|sex|shit|s hit|sh1t|slut|smegma|spunk|tit|tosser|turd|twat|vagina|wank|whore';
				
		return !preg_match('[^!@#$%^&*]*('.$profanity_filter.')[^!@#$%^&*]*', $value);
	}
}


