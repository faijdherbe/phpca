<?php

namespace Faijdherbe\PhpCa;

abstract class Application extends UI\Inflatable
{

	private static $currentApplication = null;

	public static function current() {
		return self::$currentApplication;
	}

	final public function __construct(array $info) {
		self::$currentApplication = $this;

		$this->applicationDidFinishLaunching($info);

		$this->draw(0,0,24,80);
	}

	abstract protected function applicationDidFinishLaunching(array $info);

	public function draw($x, $y, $w, $h) {
		$this->rootView()
			 ->draw($x, $y, $w, $h);
	}
}
