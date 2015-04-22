<?php

namespace Faijdherbe\PhpCa\UI;

use \Faijdherbe\PhpCa\Application;

abstract class View 
{
	private $id = null;
	private $subViews = [];

	private $target = null;
	private $attributes = [];
	private $prefixes = [];


	public final function __construct(Inflatable $target) {
		$this->target = $target;
	}

	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}

	public function findView($id) {
		if( $this->getId() == $id ) {
			return $this;
		}
		
		foreach($this->subViews as $view) {
			if(null != ($found = $view->findView($id))) {
				return $found;
			}
		}

		return null;
	}

	public function addSubview(View $subView){
		$this->subViews[] = $subView;
	}

	public function getSubviews() {
		return $this->subViews;
	}

	public function handleInput($char) {
		foreach($this->getSubviews() as $v) {
			if(true == $v->handleInput($char)) {
				return true;
			}
		}

		return false;
	}

	public function setAttributePrefix($namespace, $prefix) {
		$this->prefixes[$namespace] = $prefix;
	}

	private function prefixAttribute($key, $namespace = null)
	{
		if( null === $namespace ) {
			$namespace = static::$namespaceUri;
		}
		
		$prefix = null;

		if(isset($this->prefixes[$namespace])) {
			$prefix = $this->prefixes[$namespace];
		} else {
			throw new \UnexpectedValueException();
		}

		return sprintf('%s:%s', $prefix, $key);
	}

	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	public function getAttribute($name, $default = null, View $view = null){
		$key = $name;

		if(false === in_array($view, [null, $this])) {
			$key = $view->prefixAttribute($key);
		}

		$ret = $default;

		if (isset($this->attributes[$key])){
			$ret = $this->attributes[$key];
		}

		return $ret;
	}

	public final function isFirstResponder() {
		return $this == Application::current()->getFirstResponder();
	}

	public function canBecomeNextResponder() {
		return false;
	}

	public function becomeFirstResponder() {
		if(false == $this->canBecomeFirstResponder()) {
			throw new UnexpectedValueException('cannot become firstResponder');
		}
		Application::current()->setFirstResponder($this);
	}

	public function getNextResponder() {
		foreach($this->getSubviews as $view) {
 
		}
	}

	abstract public function draw($x, $y, $w, $h);
}
