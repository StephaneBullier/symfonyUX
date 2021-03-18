<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartController extends AbstractController
{
    /**
     * @Route("/chart", name="chart")
     * @param ChartBuilderInterface $chartBuilder
     * @return Response
     */
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'fill' => false,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderCapStyle' => 'round',
                    'borderDash' => [5, 15, 25],
                    'data' => [0, 10, 15, 2, 20, 30, 45],
                ],
            ],
        ]);


        return $this->render('chart/index.html.twig', [
            'chart' => $chart
        ]);
    }
}
