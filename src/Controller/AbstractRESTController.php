<?php
declare(strict_types=1);

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractRESTController extends AbstractFOSRestController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ValidatorInterface $validator
     * @param ObjectManager $objectManager
     */
    public function __construct(ValidatorInterface $validator, ObjectManager $objectManager)
    {
        $this->validator = $validator;
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $entityClassName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($entityClassName)
    {
        return $this
            ->getDoctrine()
            ->getRepository($entityClassName)
        ;
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function normalize($data)
    {
        return $this
            ->get('serializer')
            ->normalize($data)
        ;
    }

    /**
     * @param *Entity* $entityClass
     * @return bool
     */
    protected function validateEntity($entityClass)
    {
        return (count($this->validator->validate($entityClass)) > 0);
    }

    /**
     * @param *Entity* $entityClass
     */
    protected function persistEntity($entityClass)
    {
        $this->objectManager->persist($entityClass);
        $this->objectManager->flush();
    }

    /**
     * @param *Entity* $entityClass
     */
    protected function removeEntity($entityClass)
    {
        $this->objectManager->remove($entityClass);
        $this->objectManager->flush();
    }
}