<?php
namespace SilexPhpEngine;

use Silex\ServiceProviderInterface;
use Silex\Application;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\TemplateNameParser;


class PhpEngineServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {       
        $app['view'] = $app->share(function($app) {
            $engine  = $app['view.engine'];

            $helpers = isset($app['view.helpers']) ? $app['view.helpers'] : $app['view.default_helpers'];
            $engine->setHelpers($helpers);

            return $engine;
        });

        $app['view.engine'] = $app->share(function($app) {
            return new PhpEngine($app['view.parser'], $app['view.loader']);
        });

        $app['view.loader'] = $app->share(function($app) {
            $paths = isset($app['view.paths']) ? $app['view.paths'] : array();
            return new FileSystemLoader($paths);
        });

        $app['view.parser'] = $app->share(function() {
            return new TemplateNameParser;
        });

        $app['view.default_helpers'] = $app->share(function() {
            return array(
                new \Symfony\Component\Templating\Helper\SlotsHelper
            );
        });

        if (isset($app['view.class_path'])) {
            $app['autoloader']->registerNamespace('Symfony\\Component\\Templating', $app['view.class_path']);
        }
    }
}
