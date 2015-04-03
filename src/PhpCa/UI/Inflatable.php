<?php

namespace Faijdherbe\PhpCa\UI;

use \XMLReader as XMLReader;

abstract class Inflatable
{
	const DEFAULT_NAMESPACE = 'http://faijdherbe.net/phpca/ui';

	private static $namespaces = [
		self::DEFAULT_NAMESPACE => '\\Faijdherbe\\PhpCa\\UI'
	];

	public static function registerNamespace($namespace, $uri) {
		self::$namespaces[$uri] = $namespace;
	}

	private $rootView = null;

	protected function inflate($filename) {

		if(false == file_exists($filename)) {
			throw new \UnexpectedValueException();
		}
		
		$xml = new XMLReader();
		$xml->open($filename);

		$xml->read();

		$this->rootView = $this->inflateView($xml, null);
		
		$xml->close();
	}

	private function inflateView(XMLReader $xml, View $view = null) {

		if(XMLReader::ELEMENT !== $xml->nodeType) {
			throw new \RuntimeException();
		}

		error_log($xml->name);

		$namespace = $xml->getAttribute('xmlns');
		if(null == $namespace) {
			$namespace = self::DEFAULT_NAMESPACE;
		}
		$className = $xml->name;

		$qualifiedName = '\\' . ltrim(sprintf(
			'\\%s\\%s',
			trim(self::$namespaces[$namespace], '\\'),
			$className
		), '\\');

		$view = new $qualifiedName($this);

		if(true == $xml->moveToFirstAttribute()) {
	   		do {
				error_log(sprintf(
					'- (%s) %s: %s',
					$xml->prefix,
					$xml->localName,
					$xml->value
				));
				if('xmlns' == $xml->localName) {
					continue;
				} elseif('xmlns' == $xml->prefix) {
					$view->setAttributePrefix(
						$xml->value,
						$xml->localName
					);
				} else {
					if(null == $xml->prefix) {
						$methodName = sprintf(
							'set%s', ucfirst($xml->localName)
						);
						$view->$methodName($xml->value);
					} else {
						$view->registerAttribute($xml->name, $xml->value);
					}
				}
		    } while(true == $xml->moveToNextAttribute());
			
			$xml->moveToElement();
		}

		if(false == $xml->isEmptyElement) {
			do { 
				$xml->read();

				if(XMLReader::ELEMENT == $xml->nodeType) {
					$view->subViews[] = $this->inflateView($xml, null);
				} elseif(XMLReader::END_ELEMENT == $xml->nodeType) {
					break;
				}
		   
			} while(true);
		}

		return $view;
	}
	
	protected function rootView() {
		return $this->rootView;
	}

}
