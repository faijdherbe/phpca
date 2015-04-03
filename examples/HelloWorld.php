<?php

require (__DIR__ . '/../src/bootstrap.php');

use \Faijdherbe\PhpCa\Application;

class App extends Application
{
	private $window = null;
	
	protected function applicationDidFinishLaunching(array $info) {
		$this->inflate('layout/helloworld.xml');
	}

	private function exitButtonPressed(Button $sender) {
		$this->exit(0);
	}
}

\Faijdherbe\PhpCa\UI\Inflatable::registerNamespace(
	'\\',
	'http://test'
);

class Button extends \Faijdherbe\PhpCa\UI\Label {
	public function setOnClick($method) {
	}
}

new App([]);
