<?php

namespace Flyer\Console\Commands;

use Flyer\Console\Input\InputArgument;

class TestCommand extends Command
{
	protected $name = 'test';

	protected $description = 'Just a simple test command';

	public function prepare()
	{
		$this->addArgument('name', InputArgument::REQUIRED, 'Your name');
	}

	public function handle()
	{
		if (strlen($this->argument('name')) < 1)
		{
			$this->write("Hello!");
			return;
		}

		$this->writeln("Hello, " . $this->argument('name') . "!");
	}
}
