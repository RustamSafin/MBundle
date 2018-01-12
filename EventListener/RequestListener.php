<?php
/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 12.01.18
 * Time: 18:48
 */

namespace Rustam\MBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{


    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        var_dump($this->container->get('router')->getRouteCollection()->all());
    }
}