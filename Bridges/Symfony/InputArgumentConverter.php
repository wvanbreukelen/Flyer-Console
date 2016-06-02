<?php

namespace Flyer\Console\Bridges\Symfony;

use Flyer\Console\Input\InputArgument;
use Flyer\Console\Bridges\InputArgumentConverterInterface;
use Symfony\Component\Console\Input\InputArgument as SymfonyInputArgument;

class InputArgumentConverter implements InputArgumentConverterInterface
{
	/**
	 * Convet an input argument to an Symfony styled input argument
	 * @param  const $inputOption  Input argument
	 * @return const               Symfony styled input argument
	 */
	public static function convert($inputOption)
	{
		if ($inputOption == InputArgument::OPTIONAL)
		{
			return SymfonyInputArgument::OPTIONAL;
		} else if ($inputOption == InputArgument::REQUIRED) {
			return SymfonyInputArgument::REQUIRED;
		} else if ($inputOption == InputArgument::IS_ARRAY) {
			return SymfonyInputArgument::IS_ARRAY;
		}
	}
}
