<?php
require_once 'silex.phar';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespace('SilexPhpEngine', __DIR__.'/../src');
$loader->register();