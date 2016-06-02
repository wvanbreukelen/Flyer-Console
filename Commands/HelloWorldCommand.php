<?php

namespace Flyer\Console\Commands;

class HelloWorldCommand extends Command
{
	protected $name = 'helloworld';

	protected $description = 'Just doe an helloworld!';

	public function handle()
	{
		$this->warning("Hello World!");
	}
}
