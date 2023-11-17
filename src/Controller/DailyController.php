<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Dictionary\Entry\MealTypeDictionary;
use App\Entity\User;
use App\Factory\Entry\EntryFactory;
use App\Prodiver\Entry\EntryDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use App\Repository\EntriesRepository;
use App\Entity\UsersEntries;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductDetailsType;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    private ProductRepository $productRepository;

    private EntriesRepository $entriesRepository;

    private EntityManagerInterface $entityManager;

    private EntryDataProvider $entryDataProvider;

    private EntryFactory $factory;

    public function __construct(ProductRepository $productRepository, EntriesRepository $entriesRepository, EntityManagerInterface $entityManager, EntryDataProvider $entryDataProvider, EntryFactory $factory)
    {
        $this->productRepository = $productRepository;
        $this->entriesRepository = $entriesRepository;
        $this->entityManager = $entityManager;
        $this->entryDataProvider = $entryDataProvider;
        $this->factory = $factory;
    }

    /* WIP */
    #[Route('/product', name: 'findFood', methods: ['POST'])]
    public function findFood(Request $request): Response
    {
        $nameProduct = $request->get('search');
        $foundProducts = $this->productRepository->findProducts($nameProduct);

        return $this->render('User/Daily/Products/searchedProducts.html.twig', [
            'products' => $foundProducts,
            'nameProduct' => $nameProduct,
        ]);
    }

    #[Route('/product/{id}', name: 'addEntry', methods: ['GET|POST'])]
    public function addEntry(Request $request, Products $product, int $id): Response
    {
        $form = $this->createForm(ProductDetailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->entityManager->getRepository(User::class)->find($this->getUser()->getId());
            $product = $this->entityManager->getRepository(Products::class)->find($id);

            /* Get data counting via grammage */
            $grammarValues = $this->entryDataProvider->getGrammageValues($form->get('Grammage')->getData(), $product);

            /* Save entry */
            $this->factory->new($user, $form->get('Meals')->getData(), $grammarValues['grammage'], $product, $grammarValues['energy'], $grammarValues['protein'], $grammarValues['fat'], $grammarValues['carbohydrates']);
            $this->addFlash('success', 'Dodano wpis do dziennika');

            return $this->redirectToRoute('showEntries');
        }

        return $this->render('User/Daily/Products/productDetails.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wpisy/delete/{id}', name: 'deleteEntry')]
    public function deleteEntry(int $id): Response
    {
        $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find($id);

        if ($id) {
            $this->entityManager->remove($entry);
            $this->entityManager->flush();

            return $this->redirectToRoute('showEntries');
        } else {
            return $this->render('User/Daily/index.html.twig', []);
        }
    }

    #[Route('/wpisy/edit/{id}', name: 'editEntry', methods: ['GET|POST'])]
    public function editEntry(Request $request, int $id): Response
    {
        $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find(array('id' => $id,));
        $product = null;
        foreach ($entry->getFood() as $productDetails) {
            $product = $productDetails;
        }
        $form = $this->createForm(ProductDetailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grammarValues = $this->entryDataProvider->getGrammageValues($form->get('Grammage')->getData(), $product);
            $this->factory->edit($entry, $form->get('Meals')->getData(), $grammarValues['grammage'], $grammarValues['energy'], $grammarValues['protein'], $grammarValues['fat'], $grammarValues['carbohydrates']);

            return $this->redirectToRoute('showEntries');
        }

        return $this->render('User/Daily/Products/productDetails.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wpisy', name: 'showEntries', methods: ['GET|POST'])]
    public function showEntries(Request $request): Response
    {
        $id = $this->getUser()->getId();

        if ($request->get('dataTocheckDaily')) {
            $datetime = new \DateTime($request->get('dataTocheckDaily'));
        } else {
            $datetime = new \DateTime('@' . strtotime('now'));
        }

        return $this->render('User/Daily/index.html.twig', [
                'entry' => $this->entriesRepository->displayEntry($datetime, $id), //all entries per day, but products are not grouping in one row (meal),
                'snack' => $this->entriesRepository->ShowSnack($datetime, $id, MealTypeDictionary::SNACK), // try to fetch all entries per day for row -> Przękąski
                'breakfast' => $this->entriesRepository->ShowBreakfast($datetime, $id, MealTypeDictionary::BREAKFAST), // the same way ^ but with Śnaidanie!,
                'lunch' => $this->entriesRepository->ShowLunch($datetime, $id, MealTypeDictionary::SECOND_BREAFAST), // DRUGIE ŚNAIDANIE,
                'dinner' => $this->entriesRepository->ShowDinner($datetime, $id, MealTypeDictionary::LUNCH), // OBIAD
                'tea' => $this->entriesRepository->ShowTea($datetime, $id, MealTypeDictionary::TEA), // Podwieczorek
                'supper' => $this->entriesRepository->ShowSupper($datetime, $id, MealTypeDictionary::DINNER), // Koalcja
                'snackcal' => $this->entriesRepository->SummSnacksKcal($datetime, $id, MealTypeDictionary::SNACK), // {{ snackcal|number_format }}
                'breakcal' => $this->entriesRepository->SummBreakfast($datetime, $id, MealTypeDictionary::BREAKFAST), //  {{ breakcal|number_format }}
                'lunchkcal' => $this->entriesRepository->SummLunch($datetime, $id, MealTypeDictionary::SECOND_BREAFAST), // {{ lunchkcal|number_format }}
                'dinnerkcal' => $this->entriesRepository->SummDinner($datetime, $id, MealTypeDictionary::LUNCH), // {{ dinnerkcal|number_format }}
                'teakcal' => $this->entriesRepository->SummTea($datetime, $id, MealTypeDictionary::TEA), // {{ teakcal|number_format }}
                'supperkcal' => $this->entriesRepository->SummSupper($datetime, $id, MealTypeDictionary::DINNER), // {{ supperkcal|number_format }}
                'dataTest' => $datetime,
            ]
        );
    }
}
