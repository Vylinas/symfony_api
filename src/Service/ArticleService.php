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
     * @param Artcicle the Article to update
     * @param User the Article with new proprety
     */
    public function update(Article $article, Article $editArticle)
    {
        if($editArticle->getEmail() !== $article->getEmail())
        {
            $article->setEmail($editArticle->getEmail());
        }
        
        $this->em->persist($article);
        $this->em->flush();
    }
}