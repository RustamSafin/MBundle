<?php
/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 12.01.18
 * Time: 15:15
 */

namespace Rustam\MBundle\Routing;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MLoader extends Loader
{

    private $container;
    private $loaded = false;

    public function __construct(Container $container)
    {

        $this->container = $container;

    }

    public function load($resource, $type = null)
    {

        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "m" loader twice');
        }

        $routes = new RouteCollection();

        // prepare a new route
        $path = '/entities/';
        $defaults = array(
            '_controller' => 'Rustam\MBundle\Controller\DefaultController::indexAction',
        );
//        $requirements = array(
//            'parameter' => '\d+',
//        );
        $route = new Route($path, $defaults);

        // add the new route to the route collection
        $routeName = 'entities';
        $routes->add($routeName, $route);

        $this->loaded = true;

        return $routes;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'm' === $type;
    }
}