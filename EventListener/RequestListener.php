<?php
/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 12.01.18
 * Time: 18:48
 */

namespace Rustam\MBundle\EventListener;

use Psr\Container\ContainerInterface;
use Rustam\MBundle\Exception\RouteCollisionException;
use Rustam\MBundle\Service\EntityService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{


    private $container;
    private $service;

    public function __construct(ContainerInterface $container, EntityService $entityService)
    {
        $this->container = $container;
        $this->service = $entityService;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        $a=array();
        foreach ($this->container->get('router')->getRouteCollection()->all() as $name => $route) {
            $a[]=$route->getPath();

        }
        dump($a);
        $check1 = preg_grep('/^\/('.$this->service->getAllNamesForRequirements().')(\/)?$/',$a);
        dump($check1);
        $check2 = preg_grep('/^\/\{[a-z]+\}(\/)?$/',$a);
        if (count($check1)>0||count($check2)>1) {
            throw RouteCollisionException::routeCollision();
        }

    }
}