<?php

namespace Flyer\Console\Bridges\Symfony;

use Flyer\Console\Commands\CommandAbstract;
use Flyer\Console\Application;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandDeclarations extends CommandAbstract
{
	public function confirm($args)
	{
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion($args['question'] . " [Y/n]", $args['default']);

		return $helper->ask($this->input, $this->output, $question);
	}

	public function question($args, $secret = false)
	{
		$helper = $this->getHelper('question');
		$question = new Question(rtrim($args['question'], " ") . " ", $args['default']);

		if ($secret)
		{
			$question->setHidden(true);
			$question->setHiddenFallback(false);
		}

		return $helper->ask($this->input, $this->output, $question);
	}

	public function secret($args)
	{
		return $this->question($args, true);
	}

	public function choice($args)
	{
		$helper = $this->getHelper('question');
		$question = $args['question'] . " [Default = " . $args['possibleAnswers'][$args['default']] . "]";
		$question = new ChoiceQuestion($question, $args['possibleAnswers'], $args['default']);
		$errorMessage = $args['errorMessage'];

		if (is_null($errorMessage)) $errorMessage = 'Choice [%s] is invalid!';
		$question->setErrorMessage($errorMessage);

		return $helper->ask($this->input, $this->output, $question);
	}
}
