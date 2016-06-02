<?php

namespace Flyer\Console\Bridges;

interface BridgeInterface
{
	public function injectCommands($commands);
	public function run();
}
