<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GlobalVariablesController extends AbstractController
{
    /**
     * @Route("/globalvariables", name="global_variables")
     */
    public function index(): Response
    {
        return $this->render('global_variables/index.html.twig');
    }
}
