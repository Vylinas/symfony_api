<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Service\ArticleService;
use App\Service\OperateSerializer;

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
     * @var OperateSerializer
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
     * @var ArticleService
     */
    private $service;

    public function __construct(ArticleRepository $repo, 
                                ObjectManager $em, 
                                JsonResponse $response, 
                                ArticleService $service,
                                OperateSerializer $serializer)
    {
        $this->response     = $response;
        $this->repo         = $repo;
        $this->em           = $em;
        $this->service      = $service;
        $this->serializer   = $serializer;

    }

    /**
     * @Route("/articles", methods={"GET"} )
     * 
     * @return JsonResponse
     */
    public function getArticleAction()
    {
        $articles       = $this->repo->findAll();
        $jsonContent    = $this->serializer->encode($articles, 'json');
        
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
        $article    = $this->serializer->decode($data, Article::class, 'json');
        $this->service->create($article);

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
        $json = $this->serializer->encode($article, 'json');

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
        $this->service->delete($article);

        return $this->response->setData('Object Delete');

    }

    /**
     * @Route("/articles/{id}", methods={"PUT"} )
     * 
     * @param Article
     * @return JsonResponse
     */
    public function editArticleByIdAction(Article $article, Request $request) 
    {
        $data           = $request->getContent();
        $editArticle    = $this->serializer->decode($data, Article::class, 'json');
        $this->service->update($article, $editArticle);
        
        return $this->response->setData('Object Update');
    }

}
