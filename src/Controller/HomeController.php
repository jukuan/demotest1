<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Service\PriceCalculatorService;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    private Book $book;

    private PriceCalculatorService $priceCalculatorService;

    public function __construct(
        ContainerInterface $container,
        RequestStack $requestStack,
        PriceCalculatorService $priceCalculatorService
    ) {
        $this->container = $container; // just to be consistent with property from AbstractController

        $request = $requestStack->getCurrentRequest();
        $referrer = (null !== $request) ? $request->server->get('HTTP_REFERER') : null;

        $this->priceCalculatorService = $priceCalculatorService->setReferrer($referrer);

        $this->book = (new Book())->setBasePrice(10);
    }

    /**
     * @Route("/", name="home_index")
     */
    public function index(): Response
    {
        $price = $this->priceCalculatorService
            ->setBasePrice($this->book->getBasePrice())
            ->getPersonalPrice();

        return $this->render('home/index.twig', [
            'price' => $price,
        ]);
    }
}
