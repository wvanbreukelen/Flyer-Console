<?php

namespace Flyer\Console;

use Flyer\Console\Exceptions\ConsoleAppException;
use Flyer\Console\Commands\Command;
use Flyer\Console\Bridges\Symfony\SymfonyBridge as DefaultBridge;
use Flyer\Console\Bridges\BridgeHandler as DefaultBridgeHandler;

class Application
{

	/**
	 * Name of the console application
	 * @var string
	 */
	protected static $name;

	/**
	 * Version of the console application
	 * @var int
	 */
	protected static $version;

	protected static $bridgedApp;

	/**
	 * All registered commands
	 * @var array
	 */
	protected $commands = array();

	/**
	 * Output provider, provides the application visible output through the console
	 * @var object
	 */
	protected static $outputProvider;

	/**
	 * Input provider, receiving information from the console made easy
	 * @var object
	 */
	protected static $inputProvider;

	/**
	 * Bridge handler, the glue between console components and Flyer console package.
	 * When set to null, default handler is used
	 * @var mixed
	 */
	protected static $bridgeHandler = null;

	/**
	 * Construct new console application
	 * @param string $name    Name of your application
	 * @param mixed $version  The version of your application
	 */
	public function __construct($name, $version, $bridgeHandler = null)
	{
		$this->setName($name);
		$this->setVersion($version);
	}

	/**
	 * Run the console application
	 * @param  mixed $bridge Optional bridge handler
	 * @return mixed         Define later!
	 */
	public function run($bridge = null)
	{
		// Create a new bridge if needed
		$bridge = $this->createBridge($bridge);

		// Create a new bridge handler
		$bridgeHandler = $this->createBridgeHandler($bridge);

		// Prepare the bridge handler for first use
		$bridgeHandler->prepare();

		// Run bridge using the bridge handler
		$bridgeHandler->run();
	}

	/**
	 * Use aliases to speed up the workflow of the developer
	 * @return mixed
	 */
	public function useAliases()
	{
		class_alias('Flyer\Console\Commands\Command', 'FlyerCommand');
	}

	public function createBridge($bridge = null)
	{
		if (is_null($bridge))
		{
			$bridge = new DefaultBridge();
		}

		return $bridge;
	}

	public function createBridgeHandler($bridge, $bridgeHandler = null)
	{
		// Create a new bridge handler if needed
		if (!is_object($bridgeHandler) || is_null($bridgeHandler))
		{
			$bridgeHandler = new DefaultBridgeHandler($bridge, $this->getCommands());
		}

		$this->setBridgeHandler($bridgeHandler);

		return $bridgeHandler;
	}

	/**
	 * Add a command to the application
	 * @param Command $command Your command instance
	 */
	public function addCommand(Command $command)
	{
		$this->commands[] = $command;
	}

	/**
	 * Set the bridge handler to be used
	 * @param object $bridgeHandler Bridge handler
	 */
	public function setBridgeHandler($bridgeHandler)
	{
		static::$bridgeHandler = $bridgeHandler;
	}

	/**
	 * Return the current bridge handler
	 * @return object Bridge handler
	 */
	public static function getBridgeHandler()
	{
		return static::$bridgeHandler;
	}

	public static function setBridgedApplication($app)
	{
		static::$bridgedApp = $app;
	}

	public static function getBridgedApplication($app)
	{
		return static::$bridgedApp;
	}

	/**
	 * Set current input provider
	 * @param object $provider Input provider
	 */
	public static function setInputProvider($provider)
	{
		static::$inputProvider = $provider;
	}

	/**
	 * Set current output provider, which provide output through the console
	 * @param object $provider Output provider
	 */
	public static function setOutputProvider($provider)
	{
		static::$outputProvider = $provider;
	}

	/**
	 * Set the bridge the console application has to use
	 * @param object $bridge Your perferred bridge to use
	 */
	public function setBridge($bridge)
	{
		if (is_object($bridge))
		{
			$this->bridge = $bridge;
		} else if (is_string($bridge)) {
			if (class_exists($bridge))
			{
				$this->bridge = new $bridge();
			} else {
				throw new BridgeException();
			}
		} else {
			throw new ConsoleAppException("Cannot add bridge with variable type of " . gettype($bridge));
		}

	}

	/**
	 * Get the current input provider
	 * @return object The output provider
	 */
	public static function getInputProvider()
	{
		if (is_object(static::$inputProvider))
		{
			return static::$inputProvider;
		}

		throw new ConsoleAppException("Unable to return empty input provider");
	}

	/**
	 * Get the current output provider
	 * @return object The output provider
	 */
	public static function getOutputProvider()
	{
		if (is_object(static::$outputProvider))
		{
			return static::$outputProvider;
		}

		throw new ConsoleAppException("Unable to return empty output provider");
	}

	/**
	 * Get the application name
	 * @return string The application name
	 */
	public static function getName()
	{
		return static::$name;
	}

	/**
	 * Get the current application version
	 * @return int Application version
	 */
	public static function getVersion()
	{
		return static::$version;
	}

	/**
	 * Get application commands
	 * @return array All the commands
	 */
	protected function getCommands()
	{
		return $this->commands;
	}

	/**
	 * Set application name
	 * @param string $name The application name
	 */
	protected function setName($name)
	{
		if (is_string($name))
		{
			static::$name = $name;
			return;
		}

		throw new ConsoleAppException("Cannot set application name with type of " . gettype($name) . ", expecting string");
	}

	/**
	 * Set the current application version
	 * @param int $version Application version
	 */
	protected function setVersion($version)
	{
		if (is_int($version) || is_string($version))
		{
			static::$version = (is_int($version)) ? (int) $version : $version;
			return;
		}

		throw new ConsoleAppException("Cannot set version with type of " . gettype($version) . ", expecting integer or string");
	}
}
