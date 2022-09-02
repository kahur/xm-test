<?php

namespace App\Controller;

use App\Entity\SearchData;
use App\Forms\SearchForm;
use App\Http\Request\RapidApi\FinanceHistoryRequest;
use App\Service\RapidApiDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function index()
    {
        return $this->render('home.html.twig');
    }

    #[Route('/search')]
    public function search(Request $request, RapidApiDataService $rapidApiDataService)
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchForm::class, $searchData);
        $form->handleRequest($request);
        $data = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $rapidApiDataService->getData(
                $searchData->getCompanyCode(),
                $searchData->getStartDate(),
                $searchData->getEndDate()
            );
        }

        return $this->render('search.html.twig', [
            'form' => $form->createView(),
            'chartData' => json_encode($data)
        ]);
    }
}
