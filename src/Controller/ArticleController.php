<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;

/**
 * @Route("/api")
 * 
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleRepository
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


    public function __construct(ArticleRepository $repo, ObjectManager $em, JsonResponse $response)
    {
        $encoders           = [new XmlEncoder(), new JsonEncoder()];
        $normalizers        = [new ObjectNormalizer()];
        $this->serializer   = new Serializer($normalizers, $encoders);
        $this->response     = $response;
        $this->repo         = $repo;
        $this->em           = $em;

    }

    /**
     * @Route("/articles", methods={"GET"} )
     * 
     * @return JsonResponse
     */
    public function getArticleAction()
    {
        $articles       = $this->repo->findAll();
        $jsonContent    = $this->serializer->serialize($articles, 'json');
        
        return $this->response->fromJsonString($jsonContent);

    }

    /**
     * @Route("/articles", methods={"POST"} )
     * 
     * @param Request
     * @return JsonResponse
     */
    public function postArticleAction(Request $request)
    {
        $data       = $request->getContent();
        $article    = $this->serializer->deserialize($data, Article::class, 'json');

        $this->em->persist($article);
        $this->em->flush();

        return $this->response->setData('Object Create');

    }

    /**
     * @Route("/articles/{id}", methods={"GET"} )
     * 
     * @param Article
     * @return JsonResponse
     */
    public function getArticleByIdAction(Article $article)
    {
        $json = $this->serializer->serialize($article, 'json');
        return $this->response->fromJsonString($json);

    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"} ) 
     * 
     * @param Article
     * @return JsonResponse
     */
    public function deleteArticleByIdAction(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
        return $this->response->setData('Object Delete');

    }

    /**
     * @Route("articles/{id}", methods={"PUT"} )
     * 
     * @param Article
     * @return JsonResponse
     */
    public function updateArticleByIdAction(Article $article) 
    {
        $this->em->persist($article);
        $this->em->flush();
        return $this->response->setData('Object Update');
    }

}
