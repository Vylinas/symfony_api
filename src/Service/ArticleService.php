<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Article;

class ArticleService
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Article
     */
    public function create(Article $article)
    {
        $article->setIsActive(true);
        $article->setCreatedAt(date('Y-m-d H:i:s'));
        $this->em->persist($article);
        $this->em->flush();
    }

    /**
     * @param Article
     */
    public function delete(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }

    /**
     * @param Article the Article to update
     * @param Article the Article with new proprety
     */
    public function update(Article $article, Article $editArticle)
    {
        if($editArticle->getName() !== $article->getName())
        {
            $article->setName($editArticle->getName());
        }
        if($editArticle->getDescription() !== $article->getDescription())
        {
            $article->setDescription($editArticle->getDescription());
        }
        $article->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->em->persist($article);
        $this->em->flush();
    }

}