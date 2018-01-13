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
use Rustam\MBundle\Service\EntityService;

class MLoader extends Loader
{

    private $container;
    private $service;
    private $loaded = false;

    public function __construct(Container $container, EntityService $service)
    {
        $this->service = $service;
        $this->container = $container;

    }

    public function load($resource, $type = null)
    {

        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "m" loader twice');
        }

        $routes = new RouteCollection();

        // prepare a new route
        $path1 = '/{entities}/';
        $path2 = '/{entities}/{id}';
        $defaults1 = array(
            '_controller' => 'Rustam\MBundle\Controller\DefaultController::indexEntity',
        );
        $defaults2 = array(
            '_controller' => 'Rustam\MBundle\Controller\DefaultController::indexEntityById',
        );
//        $requirements = array(
//            'parameter' => '\d+',
//        );
        $requirements1 = array(
            'entities' => $this->service->getAllNamesForRequirements(),
        );
        $requirements2 = array(
            'id' => '\d+',
            'entities' => $this->service->getAllNamesForRequirements(),
        );
        $route1 = new Route($path1, $defaults1,$requirements1);
        $route2 = new Route($path2, $defaults2,$requirements2);


        // add the new route to the route collection

        $routes->add('entity', $route1);
        $routes->add('entityById', $route2);


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