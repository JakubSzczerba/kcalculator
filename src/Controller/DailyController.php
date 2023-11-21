<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Command\Daily\AddEntryCommand;
use App\Command\Daily\EditEntryCommand;
use App\Dictionary\Entry\MealTypeDictionary;
use App\DTO\EntryDTO;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use App\Repository\EntriesRepository;
use App\Entity\UsersEntries;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductDetailsType;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    private ProductRepository $productRepository;

    private EntriesRepository $entriesRepository;

    private EntityManagerInterface $entityManager;

    private MessageBusInterface $commandBus;

    public function __construct(ProductRepository $productRepository, EntriesRepository $entriesRepository, EntityManagerInterface $entityManager, MessageBusInterface $commandBus)
    {
        $this->productRepository = $productRepository;
        $this->entriesRepository = $entriesRepository;
        $this->entityManager = $entityManager;
        $this->commandBus = $commandBus;
    }

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
        $user = $this->entityManager->getRepository(User::class)->find($this->getUser()->getId());
        $product = $this->entityManager->getRepository(Products::class)->find($id);

        $form = $this->createForm(ProductDetailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entryDTO = new EntryDTO(
                $form->get('Grammage')->getData(), $form->get('Meals')->getData(), $product
            );

            $command = new AddEntryCommand($entryDTO, $user);
            $this->commandBus->dispatch($command);
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
            return $this->render('User/Daily/index.html.twig');
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
            $entryDTO = new EntryDTO(
                $form->get('Grammage')->getData(), $form->get('Meals')->getData(), $product
            );

            $command = new EditEntryCommand($entryDTO, $entry);
            $this->commandBus->dispatch($command);

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
