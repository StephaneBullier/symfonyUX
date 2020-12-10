<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiltersController extends AbstractController
{
    /**
     * @Route("/filters", name="filters")
     */
    public function index(): Response
    {
        return $this->render('filters/index.html.twig');
    }
}
