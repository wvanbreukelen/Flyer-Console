<?php

namespace Flyer\Console\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Flyer\Console\Application;

abstract class CommandAbstract
{
	/**
	 * Write to console
	 * @param  string $message The message you want to display
	 * @return none
	 */
	public function write($message)
	{
		$this->output()->write($message);
	}

	/**
	 * Write a single line to the console
	 * @param  string $message The single line message you want to display
	 * @return none
	 */
	public function writeln($message)
	{
		$this->output()->writeln($message);
	}

	/**
	 * Display an error message
	 * @param  string $message Your desired error message
	 * @return none
	 */
	public function error($message)
	{
		$this->output()->writeln($this->enhanceWithTags('error', $message));
	}

	/**
	 * Display an warning message
	 * @param  string $message Your desired warning message
	 * @return none
	 */
	public function warning($message)
	{
		$this->output()->writeln('<fg=yellow>' . $message . '</>');
	}

	/**
	 * Display an debug message, alias for 'info' method
	 * @param  string $message Your desired debug message
	 * @return none
	 */
	public function debug($message)
	{
		$this->info($message);
	}

	/**
	 * Display an info message
	 * @param  string $message Your desired informational message
	 * @return none
	 */
	public function info($message)
	{
		$this->output()->writeln('<fg=blue>' . $message . '</>');
	}

	/**
	 * Display an success message
	 * @param  string $message Your desired success message
	 * @return none
	 */
	public function success($message)
	{
		$this->output()->writeln('<fg=green>' . $message . '</>');
	}

	/**
	 * Get an argument, leave empty for all arguments
	 * @param  string $key Argument name/identifier
	 * @return mixed       Arguments of argument output
	 */
	public function argument($key = null)
	{
		if (is_null($key))
		{
			return $this->input()->getArguments();
		}

		return $this->input()->getArgument($key);
	}

	/**
	 * Return all arguments as an array
	 * @return array All arguments
	 */
	public function arguments()
	{
		return $this->argument();
	}

	/**
	 * Ask your console audience a message
	 * @param  string $question  Your desired message
	 * @param  mixed  $default   Default answer
	 * @return mixed             Answer
	 */
	public function ask($question, $default = null)
	{
		return $this->bridge()->perform('question', ['question' => $question, 'default' => $default]);
	}

	/**
	 * Ask your console audience secretly a message
	 * @param  string $question Your desired message
	 * @param  mixed $default   Default answer
	 * @return mixed            Answer
	 */
	public function secret($question, $default = null)
	{
		return $this->bridge()->perform('secret', ['question' => $question, 'default' => $default]);
	}

	public function confirm($question, $defaultAnswer = false)
	{
		return $this->bridge()->perform('confirm', ['question' => $question, 'default' => $defaultAnswer]);
	}

	public function choice($question, array $possibleAnswers, $defaultAnswer = 0, $errorMessage = null)
	{
		return $this->bridge()->perform('choice', [
			'question' => $question,
			'possibleAnswers' => $possibleAnswers,
			'default' => (int) $defaultAnswer,
			'errorMessage' => $errorMessage,
		]);
	}

	/**
	 * Get the application input provider
	 * @return mixed The input provider
	 */
	public function input()
	{
		return Application::getInputProvider();
	}

	/**
	 * Get the application output provider
	 * @return mixed The output provider
	 */
	public function output()
	{
		return Application::getOutputProvider();
	}

	public function bridge()
	{
		return Application::getBridgeHandler();
	}

	/**
	 * Enhance a console message with tags
	 * @param  string $tag  Tag
	 * @param  string $text Your desired message
	 * @return string       Enhanced console message with tags
	 */
	protected function enhanceWithTags($tag, $text)
	{
		return '<' . $tag . '>' . $text . '</' . $tag . '>';
	}
}
