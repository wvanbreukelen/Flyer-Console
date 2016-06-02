<?php

namespace Flyer\Console\Commands;

use Flyer\Console\Exceptions\ConsoleAppException;

class Command extends CommandAbstract
{
	/**
	 * Command name
	 * @var string
	 */
	protected $name;

	/**
	 * The command description
	 * @var string
	 */
	protected $description;

	/**
	 * The action to trigger when this command is fired
	 * @var object
	 */
	protected $action;

	/**
	 * Command usage
	 * @var array
	 */
	protected $usage = array();

	/**
	 * All command arguments
	 * @var array
	 */
	protected $arguments = array();

	public function __construct()
	{
		// Running prepare method if required
		if (method_exists($this, 'prepare')) $this->prepare();
	}

	/**
	 * Add a new arguments
	 * @param string $identifier The argument name/identifier
	 * @param mixed $level       Argument level
	 * @param string $desc       Argument description
	 */
	public function addArgument($identifier, $level, $desc)
	{
		if (!isset($this->arguments[$identifier]))
		{
			$this->arguments[$identifier] = [
				'name' => $identifier,
				'level' => $level,
				'description' => $desc
			];
		}
	}

	/**
	 * Remove a single argument by his name/identifier
	 * @return none
	 */
	public function removeArgument($identifier)
	{
		if (isset($this->arguments[$identifier]))
		{
			unset($this->arguments[$identifer]);
		}
	}

	/**
	 * Set the command trigger action
	 * @param object $action Anomynous function
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}

	/**
	 * Get the command trigger action
	 * @return object Anomynous function
	 */
	public function getAction()
	{
		// Using anomynous function
		if (is_callable($this->action))
		{
			return $this->action;
		}

		// Using extending class
		if (method_exists($this, 'handle'))
		{
			// Return dummy anomynous function
			return function ()
			{
				$this->handle();
			};
		}

		throw new ConsoleAppException("Unable to receive action for " . $this->getName() . " command");
	}

	/**
	 * Set command name
	 * @param string $name Command name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get command name
	 * @return string Command name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set command description
	 * @param string $desc Command description
	 */
	public function setDescription($desc)
	{
		$this->description = $desc;
	}

	/**
	 * Get command description
	 * @return string Command description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get command arguments
	 * @return array Command arguments
	 */
	public function getArguments()
	{
		return $this->arguments;
	}
}
