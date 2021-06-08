<?php

namespace App\Custom\Services;

class ValidationService
{
	public static function isLocale($mixed)
	{
		return in_array($mixed, array_keys(config('custom.languages')));
	}

	public static function isVm($mixed)
	{
		return (is_string($mixed) || is_int($mixed))
			? [] === array_diff([$mixed], array_keys(config('custom.visibility_modes')))
			: false;
	}

	public static function isLg($mixed)
	{
		return in_array($mixed, array_keys(config('custom.link_groups')));
	}
}