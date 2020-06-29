<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    private RequestStack $requestStack;

    private Book $book;

    private PriceCalculatorService $priceCalculatorService;

    public function __construct(RequestStack $requestStack, PriceCalculatorService $priceCalculatorService)
    {
        $request = $requestStack->getCurrentRequest();
        $referrer = (null !== $request) ? $request->server->get('HTTP_REFERER') : null;

        $this->priceCalculatorService = $priceCalculatorService->setReferrer($referrer);

        $this->book = (new Book())->setBasePrice(10);
    }

    /**
     * @Route("/", name="home_index")
     */
    public function index()
    {
        $price = $this->priceCalculatorService
            ->setBasePrice($this->book->getBasePrice())
            ->getPersonalPrice();

        return $this->render('home/index.twig', [
            'price' => $price,
        ]);
    }
}
