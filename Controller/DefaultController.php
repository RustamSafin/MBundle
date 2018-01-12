<?php

namespace Rustam\MBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
//    /**
//     * @Route("/entities")
//     */
    public function indexAction()
    {
        $metadata = $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata();
        $choices = [];
        foreach ($metadata as $classMeta) {
            $choices[] = $classMeta->getName(); // Entity FQCN
        }
        // replace this example code with whatever you need
        return new Response(json_encode($choices));
    }

//    /**
//     * @Route("/{entityName}/")
//     */
    public function indexEntity($entityName, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $choice= '';
        foreach ($metadata as $classMeta) {
            if (preg_match('/.*'.$entityName.'$/',$classMeta->getName())){
                $choice = $classMeta;
                break;
            }
        }
        if ($request->getMethod()==='POST') {
            $postData = file_get_contents('php://input');
            $data = json_decode($postData, true);
            $entity = $choice->getName();
            $entity = new $entity();
            $fields = $choice->getFieldNames();
            foreach ($fields as $field) {
                if (array_key_exists($field, $data)) {
                    $set = 'set' . ucfirst($field);
                    $entity->$set($data[$field]);
                }
            }
            $em->persist($entity);
            $em->flush();
        }
        $res = $em->getRepository($choice->getName())->findAll();
        $a = array();
        $a [$choice->getName()] = $choice->getFieldNames();
        foreach ($res as $r) {
            $a [] = array_values((array) $r);
        }

        return new Response(json_encode($a));

    }

    /**
     * @Route("/{entityName}/{id}")
     */
    public function indexEntityById($entityName, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->find($entityName,$id);
        return new Response(json_encode($entity));
    }
}
