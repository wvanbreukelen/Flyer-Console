<?php

namespace Flyer\Console\Bridges;

use Flyer\Console\Exceptions\BridgeException;
use Flyer\Console\Exceptions\ConsoleAppException;

/**
 * The bridge handler is the glue between Flyer-Console and other console project, like symfony/console.
 */
class BridgeHandler
{
	private $commands = array();

	public function __construct($bridge, array $commands = array())
	{
		$this->commands = $commands;

		// Check if given bridge is valid
		if (!is_object($bridge))
		{
			throw new BridgeException("Please supply a valid bridge instance to bridge handler!");
		}

		$this->bridge = $bridge;
	}

	public function prepare()
	{
		// Inject commands into bridge
		$this->bridge->injectCommands($this->commands);
	}

	public function perform($method, array $arguments = array())
	{
		if (method_exists($this->bridge, $method))
		{
			return $this->bridge->$method($arguments);
		}

		throw new BridgeException("Bridge " . get_class($this->bridge) . " doesn't have a " . $method . " method. Please make sure the action " . $method . " is available!");
	}

	public function run()
	{
		// Run the bridge
		$this->bridge->run();
	}
}
