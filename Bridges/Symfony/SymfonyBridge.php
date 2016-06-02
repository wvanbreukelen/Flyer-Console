<?php

namespace Flyer\Console\Bridges\Symfony;

use Flyer\Console\Bridges\BridgeInterface;
use Flyer\Console\Commands\Command;
use Flyer\Console\Application;
use Flyer\Console\Exceptions\BridgeException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Application as SymfonyConsoleApp;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class SymfonyBridge extends CommandDeclarations implements BridgeInterface
{
	/**
	 * Holds all of the Flyer console commands
	 * @var array
	 */
	protected $commands;

	/**
	 * Symfony console application instance
	 * @var object
	 */
	protected $app;

	/**
	 * Input argument instance
	 * @var object
	 */
	protected $input;

	/**
	 * Console output instance
	 * @var object
	 */
	protected $output;

	/**
	 * Provide the Symfony bridge commands
	 * @param  array $commands  The provided commands
	 * @return none
	 */
	public function injectCommands($commands)
	{
		$this->commands = $commands;
	}

	/**
	 * Run the Symfony bridge
	 * @return none
	 */
	public function run()
	{
		// First, let's create a new Symfony console application
		$app = $this->createSymfonyConsoleApplication();

		// Set important application information
		$app->setName(Application::getName());
		$app->setVersion(Application::getVersion());

		// Force Symfony application to not auto exit
		$app->setAutoExit(false);

		// Add all of our commands to the Symfony console command holder
		foreach ($this->commands as $command)
		{
			$app->add($this->createSymfonyCommand($command));
		}

		// Build some new input and output interfaces
		$this->input = new ArgvInput();
		$this->output = new ConsoleOutput();

		// Share those input and output providers with the application
		Application::setInputProvider($this->input);
		Application::setOutputProvider($this->output);

		Application::setBridgedApplication($app);

		// Set application
		$this->app = $app;

		// Let's run your freshly prepared Symfony application!
		$app->run($this->input, $this->output);
	}

	/**
	 * Create a new Symfony based command
	 * @param  Command $command An Flyer console command
	 * @return none
	 */
	protected function createSymfonyCommand(Command $command)
	{
		try {
			$sfCommand = new SymfonyCommand($command->getName());

			$sfCommand->setDescription($command->getDescription());
			$sfCommand->setCode($command->getAction());

			$this->addSymfonyCommandArguments($command, $sfCommand);

			return $sfCommand;
		} catch (Exception $e) {
			throw new BridgeException("Unable to create " . $command->getName() . " command using Symfony, error: " . $e->getMessage());
		}
	}

	/**
	 * Merge arguments from an general command to a Symfony based command
	 * @param Command        $command   An flyer console command
	 * @param SymfonyCommand $sfCommand An Symfony console command
	 */
	protected function addSymfonyCommandArguments(Command $command, SymfonyCommand $sfCommand)
	{
		$args = $command->getArguments();

		foreach ($args as $arg)
		{
			$sfCommand->addArgument(
				$arg['name'],
				InputArgumentConverter::convert($arg['level']),
				$arg['description']
			);
		}
	}

	/**
	 * Create an new Symfony console application
	 * @return SymfonyConsoleApp The new Symfony console application
	 */
	protected function createSymfonyConsoleApplication()
	{
		return new SymfonyConsoleApp;
	}

	/**
	 * Return the Symfony console application instance
	 * @return SymfonyConsoleApp The console application
	 */
	protected function getApplication()
	{
		return $this->app;
	}

	/**
	 * Receive a certain helper from Symfony
	 * @param  string $helper The preferred helper
	 * @return object         The helper itself
	 */
	protected function getHelper($helper)
	{
		$helperSet = $this->getApplication()->getHelperSet();

		if ($helperSet->get($helper) !== null)
		{
			return $helperSet->get($helper);
		}
	}
}
