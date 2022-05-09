<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $repoArticle;

    public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategory = $repoCategory;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $articles = $this->repoArticle->findAll();
        $categories = $this->repoCategory->findAll();

        return $this->render('home/index.html.twig',[
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    // en injectant direc l'objet article, symfony trouvera la ligne correspondant à l'id
    // grâce au paramètre id
    // ce qui permet d'économiser cette ligne  $article = $this->repoArticle->find($id); 
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article): Response
    {
        if (!$article) {
            return $this->redirectToRoute('home');
        }

        return $this->render('show/index.html.twig',[
            'article' => $article
        ]);
    }
    /**
     * @Route("/category/{id}", name="category")
     */
    public function showArticlesByCategory(?Category $categorie): Response
    {
        if (!$categorie) {
            return $this->redirectToRoute('home');
        }

        $articles = $categorie->getArticles();
        $categories = $this->repoCategory->findAll();

        return $this->render('home/index.html.twig',[
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
