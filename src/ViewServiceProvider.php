<?php
namespace SilexPhpEngine;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\TemplateNameParser;


class ViewServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['view'] = function($app) {
            $engine  = $app['view.engine'];

            $helpers = isset($app['view.helpers']) ? $app['view.helpers'] : array();
            $helpers = array_merge($helpers, $app['view.default_helpers']);
            $engine->setHelpers($helpers);

            return $engine;
        };

        $app['view.engine'] = function($app) {
            return new PhpEngine($app['view.parser'], $app['view.loader']);
        };

        $app['view.loader'] = function($app) {
            $paths = isset($app['view.paths']) ? $app['view.paths'] : array();
            return new FileSystemLoader($paths);
        };

        $app['view.parser'] = function() {
            return new TemplateNameParser;
        };

        $app['view.default_helpers'] = function() {
            return array(
                new \Symfony\Component\Templating\Helper\SlotsHelper
            );
        };
    }
}
