<?php
namespace Parcel;
use Symfony\Component\Templating\Helper\Helper;
use Silex\Application;

class AssetsHelper extends Helper
{
  protected $config;

  /**
   * Construct helper from configuration
   *
   * @param array
   */
  public function __construct(array $config)
  {
    $this->config = array_merge(array(
      'debug' => false,
      'root'  => '',
      'server' => array(
        'port' => 8000,
        'host' => '127.0.0.1'
      ),
    ), $config);

    $this->config['root'] = rtrim($this->config['root'], '/');
  }


  /**
   * Create helper from Silex application
   *
   * @param Silex\Application
   * @return Parcel\AssetsHelper
   */
  static public function create(Application $app)
  {
    $config = isset($app['assets']) ? $app['assets'] : array();

    if (!isset($config['debug'])) {
      $config['debug'] = $app['debug'];
    }

    return new self($config);
  }


  /**
   * Get bundle tags
   *
   * @param string|array $bundles
   * @return string
   */
  public function bundle($bundles)
  {
    $bundles = (array) $bundles;
    $output  = array();

    foreach ($bundles as $bundle) {
      if (!$this->config['debug']) {
        $ext = substr($bundle, strpos($bundle, '.')+1);
        $output[] = $this->{$ext}($this->config['root'].'/'.$bundle);
      } else {
        $root = "http://{$this->config['server']['host']}:{$this->config['server']['port']}";
        $output[] = $this->js($root.'/?'.$bundle);
      }
    }

    return implode("\n", $output);
  }


  /**
   * CSS link tag
   *
   * @param string $uri
   * @return string
   */
  public function css($uri)
  {
    return sprintf('<link rel="stylesheet" href="%s">', $uri);
  }


  /**
   * JS script tag
   *
   * @param string $uri
   * @return string
   */
  public function js($uri)
  {
    return sprintf('<script src="%s"></script>', $uri);
  }


  public function getName()
  {
    return 'assets';
  }
}
