<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: 'GET')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render(
            'home.html.twig', 
            [
                "dataSlider" => $articleRepository->findBy([], ['createdAt' => 'ASC'], 4),
                "dataArticles" => $articleRepository->findAll(),
                "last_username" => '',
                "error" => ''
            ]
        );
    }
}