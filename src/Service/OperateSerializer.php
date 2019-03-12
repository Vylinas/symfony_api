<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class OperateSerializer
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct()
    {
        $encoders           = [new XmlEncoder(), new JsonEncoder()];
        $normalizers        = [new ObjectNormalizer()];
        $this->serializer   = new Serializer($normalizers, $encoders);
    }

    /**
     * @param $data waiting object|array|string to convert into Json
     * @return json decode 
     */
    public function encode($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * @param $data waiting json
     * @param $class wainting class to input value of
     * @return Object given with set value
     */
    public function decode($data, $class)
    {
        return $this->serializer->deserialize($data, $class, 'json');
    }
}
