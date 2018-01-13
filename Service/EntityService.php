<?php
/**
 * Created by PhpStorm.
 * User: rustam
 * Date: 13.01.18
 * Time: 13:00
 */

namespace Rustam\MBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;


class EntityService
{
    private $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update($entityName, $id, $content)
    {
        $classMeta = $this->getClassMetadata(ucfirst($entityName));
        if (empty($classMeta)) {
            return new Response('no such entity');
        }
        $repository = $this->entityManager->getRepository($classMeta->getName());
        $entity = $repository->find($id);
        $fields = $classMeta->getFieldNames();
        foreach ($fields as $field) {
            if (array_key_exists($field, $content)) {
                $set = 'set' . ucfirst($field);
                $entity->$set($content[$field]);
            }
        }
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new Response("updated");
    }

    public function delete($entityName, $id)
    {
        $classMeta = $this->getClassMetadata(ucfirst($entityName));
        if (empty($classMeta)) {
            return new Response('no such entity');
        }
        $repository = $this->entityManager->getRepository($classMeta->getName());
        $entity = $repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return new Response('deleted');
    }

    public function findById($entityName, $id)
    {
        $classMeta = $this->getClassMetadata(ucfirst($entityName));
        if (empty($classMeta)) {
            return new Response('no such entity');
        }
        $repository = $this->entityManager->getRepository($classMeta->getName());
        return $repository->findOneById($id);
    }
    public function save($entityName, $content ) {
        $classMeta = $this->getClassMetadata(ucfirst($entityName));
//        $postData = file_get_contents('php://input');
//        $data = json_decode($postData, true);
        if (empty($classMeta)) {
            return new Response('no such entity');
        }
        $entity = $classMeta->getName();
        $entity = new $entity();
        $fields = $classMeta->getFieldNames();
        foreach ($fields as $field) {
            if (array_key_exists($field, $content)) {
                $set = 'set' . ucfirst($field);
                $entity->$set($content[$field]);
            }
        }
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new Response('saved');
    }

    public function findEntityByName($entityName) {
        $classMeta = $this->getClassMetadata(ucfirst($entityName));
        if (empty($classMeta)) {
            return new Response('no such entity');
        }
        $res = $this->entityManager->getRepository($classMeta->getName())->findAll();
        $a = array();
        $a [$classMeta->getName()] = $classMeta->getFieldNames();
        foreach ($res as $r) {
            $a [] = array_values((array) $r);
        }
        return $a;
    }
    public function getAll() {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $choices = [];
        foreach ($metadata as $classMeta) {
            $choices[] = $classMeta->getName();
        }
        return $choices;
    }
    public function getAllNamesForRequirements() {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $choices = '';
        foreach ($metadata as $classMeta) {
            $e = explode('\\',$classMeta->getName());
            $choices .= strtolower(end($e)).'|';
        }
        return substr($choices,0,strlen($choices)-1);
    }
    private function getClassMetadata($entityName) {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $choice= '';
        foreach ($metadata as $classMeta) {
            if (preg_match('/.*'.$entityName.'$/',$classMeta->getName())){
                $choice = $classMeta;
                break;
            }
        }
        return $choice;
    }
}