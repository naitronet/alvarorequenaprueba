<?php

$namespaces = [
	'Services\\' => PATH_ROOT.'../managers/',
	'Managers\\' => PATH_ROOT.'../services/',
];

spl_autoload_register('autoloader');

function autoloader($namespace)
{
	global $namespaces;

	foreach ($namespaces as $dir) {
		$className = pathinfo($namespace, PATHINFO_FILENAME);
		$pathClass = $dir.$className.'.php';

		if (file_exists($pathClass)) {
			include_once $pathClass;
			return;
		}
	}

	throw new \Exception("La clase '{$className}' no se ha podido encontrar.", 1);
}