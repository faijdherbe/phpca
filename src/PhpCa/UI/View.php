<?php

namespace Faijdherbe\PhpCa\UI;

abstract class View 
{
	private $name = null;
	private $subViews = [];

	private $target = null;

	public final function __construct(Inflatable $target) {
		$this->target = $target;
	}

	public function getName() {
		return $this->name;
	}

	protected function viewNamed($name) {
		if( $this->getName() == $name ) {
			return $this;
		}
		
		foreach($this->subViews as $view) {
			if(null != ($found = $view->viewNamed($name))) {
				return $found;
			}
		}

		return null;
	}

	private $prefixes = [];

	public function setAttributePrefix($namespace, $prefix) {
		$this->prefixes[$namespace] = $prefix;
	}

	private $attributes = [];
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	public function getAttribute($name){
		return $this->attributes[$name];
	}

	public function getAttributeFromView(View $view, $attribute) {
		$prefix = 'x';

		if(isset($this->prefixes[self::$namespaceUri])) {
			$prefix = $this->prefixes[self::$namespaceUri];
		}

		return $view->getAttribute(sprintf(
			'%s:%s',
			$prefix,
			$attribute
		));
	}

	abstract public function draw($x, $y, $w, $h);
}
