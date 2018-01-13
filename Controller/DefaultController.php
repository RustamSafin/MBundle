<?php

namespace Rustam\MBundle\Controller;

use Rustam\MBundle\Service\EntityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    private $entityService;

    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    public function indexAction()
    {
        return new Response(json_encode($this->entityService->getAll()));
    }


    public function indexEntity($entityName, Request $request)
    {
        if ($request->getMethod()==='POST') {
            return $this->entityService->save($entityName,json_decode($request->getContent(),true));
        }
        $response = $this->entityService->findEntityByName($entityName);
        return new Response(json_encode($response));

    }


    public function indexEntityById($entityName, $id, Request $request)
    {

        if ($request->getMethod()==="PUT") {
           return $this->entityService->update($entityName,$id,json_decode($request->getContent(),true));
        }
        if ($request->getMethod()==="DELETE"){
            $response = $this->entityService->delete($entityName,$id);
            return $response;
        }

        $entity = $this->entityService->findById($entityName,$id);
        return new Response(json_encode($entity));

    }


}
