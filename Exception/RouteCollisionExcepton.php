<?php
/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 13.01.18
 * Time: 18:59
 */

namespace Rustam\MBundle;




class RouteCollisionExcepton extends \Exception
{
    public static function routeCollision(){
        return new self('Multiple Routes have matched to this request. See route definitions');
    }
}