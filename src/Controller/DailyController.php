<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Controller;

use Kcalculator\Command\Daily\AddEntryCommand;
use Kcalculator\Command\Daily\EditEntryCommand;
use Kcalculator\DTO\EntryDTO;
use Kcalculator\Entity\User;
use Kcalculator\Query\Daily\DailyEntriesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Repository\ProductRepository;
use Kcalculator\Entity\UsersEntries;
use Kcalculator\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use Kcalculator\Form\ProductDetailsType;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    private ProductRepository $productRepository;

    private EntityManagerInterface $entityManager;

    private MessageBusInterface $commandBus;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager, MessageBusInterface $commandBus)
    {
        $this->productRepository = $productRepository;
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
        if ($request->get('dataToCheckDaily')) {
            $dateTime = new \DateTime($request->get('dataToCheckDaily'));
        } else {
            $dateTime = new \DateTime('@' . strtotime('now'));
        }

        try {
            $query = new DailyEntriesQuery($dateTime, $this->getUser()->getId());
            $envelope = $this->commandBus->dispatch($query);
            $handledStamp = $envelope->last(HandledStamp::class);

            return $this->render('User/Daily/index.html.twig',
                $handledStamp->getResult()
            );
        } catch (\Exception $e) {
            return $this->render('User/Daily/index.html.twig');
        }
    }
}
