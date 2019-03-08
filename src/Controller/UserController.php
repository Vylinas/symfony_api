<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Service\UserService;

/**
 * @Route("/api")
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var JsonResponse
     */
    private $response;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UserService
     */
    private $service;

    
    public function __construct(UserRepository $repo, 
                                ObjectManager $em, 
                                JsonResponse $response,
                                UserPasswordEncoderInterface $encoder,
                                UserService $service)
    {
        $encoders           = [new XmlEncoder(), new JsonEncoder()];
        $normalizers        = [new ObjectNormalizer()];
        $this->serializer   = new Serializer($normalizers, $encoders);
        $this->response     = $response;
        $this->repo         = $repo;
        $this->em           = $em;
        $this->encoder      = $encoder;
        $this->service      = $service;

    }

    /**
     * @Route("/users", methods={"POST"} )
     * 
     * @param Request
     * @return JsonResponse
     */
    public function postArticleAction(Request $request)
    {
        $data  = $request->getContent();
        $user  = $this->serializer->deserialize($data, User::class, 'json');
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $this->response->setData('Object Create');

    }

    /**
     * @Route("/users/{id}", methods={"GET"} )
     * 
     * @param User
     * @return JsonResponse
     */
    public function getUserByIdAction(User $user)
    {
        $json = $this->serializer->serialize($user, 'json');
        return $this->response->fromJsonString($json);

    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"} ) 
     * 
     * @param User
     * @return JsonResponse
     */
    public function deleteArticleByIdAction(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->response->setData('Object Delete');

    }

    /**
     * @Route("/users/{id}", methods={"PUT"} )
     * 
     * @param User
     * @return JsonResponse
     */
    public function editUserByIdAction(User $user, Request $request)
    {
        $data       = $request->getContent();
        $editUser   = $this->serializer->deserialize($data, User::class, 'json');
        $this->service->update($user, $editUser);

        return $this->response->setData('Object Update');

    }

}