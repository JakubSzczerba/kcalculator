<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Kcalculator\Application\Form\ProductDetailsType;
use Kcalculator\Application\Query\Daily\DailyEntriesQuery;
use Kcalculator\Domain\Entry\Entity\Entry;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\Product\ProductRepositoryInterface;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\MealJournal\Application\Command\AddMealEntryCommand;
use Kcalculator\MealJournal\Application\Command\DeleteMealEntryCommand;
use Kcalculator\MealJournal\Application\Command\EditMealEntryCommand;
use Kcalculator\MealJournal\Application\Exception\MealEntryNotFound;
use Kcalculator\MealJournal\Application\Port\FoodProductLookup;
use Kcalculator\MealJournal\Application\Port\MealEntryLookup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly FoodProductLookup $foodProductLookup,
        private readonly MealEntryLookup $mealEntryLookup,
        private readonly MessageBusInterface $commandBus,
    ) {
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

    #[Route('/product/{id}', name: 'addEntry', methods: ['GET', 'POST'])]
    public function addEntry(Request $request, int $id): Response
    {
        $product = $this->foodProductLookup->findById($id);

        if ($product === null) {
            throw $this->createNotFoundException(sprintf('Product with id %d was not found.', $id));
        }

        $form = $this->createForm(ProductDetailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new AddMealEntryCommand(
                $this->getAuthenticatedUser()->getId(),
                $product->getId(),
                (string) $form->get('Meals')->getData(),
                (float) $form->get('Grammage')->getData(),
            );

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
        $user = $this->getAuthenticatedUser();
        $entry = $this->mealEntryLookup->findOwnedById($id, $user->getId());

        if ($entry === null) {
            throw $this->createNotFoundException(sprintf('Meal entry with id %d was not found.', $id));
        }

        try {
            $this->commandBus->dispatch(new DeleteMealEntryCommand($user->getId(), $id));
        } catch (MealEntryNotFound $exception) {
            throw $this->createNotFoundException($exception->getMessage(), $exception);
        }

        $this->addFlash('success', 'Usunięto wpis z dziennika');

        return $this->redirectToRoute('showEntries');
    }

    #[Route('/wpisy/edit/{id}', name: 'editEntry', methods: ['GET', 'POST'])]
    public function editEntry(Request $request, int $id): Response
    {
        $user = $this->getAuthenticatedUser();
        $entry = $this->mealEntryLookup->findOwnedById($id, $user->getId());

        if (!$entry instanceof Entry) {
            throw $this->createNotFoundException(sprintf('Meal entry with id %d was not found.', $id));
        }

        $product = $this->extractProductFromEntry($entry);
        $form = $this->createForm(ProductDetailsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->dispatch(new EditMealEntryCommand(
                    $user->getId(),
                    $id,
                    (string) $form->get('Meals')->getData(),
                    (float) $form->get('Grammage')->getData(),
                ));
            } catch (MealEntryNotFound $exception) {
                throw $this->createNotFoundException($exception->getMessage(), $exception);
            }

            $this->addFlash('success', 'Zaktualizowano wpis w dzienniku');

            return $this->redirectToRoute('showEntries');
        }

        return $this->render('User/Daily/Products/productDetails.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wpisy', name: 'showEntries', methods: ['GET', 'POST'])]
    public function showEntries(Request $request): Response
    {
        if ($request->get('dataToCheckDaily')) {
            $dateTime = new \DateTime($request->get('dataToCheckDaily'));
        } else {
            $dateTime = new \DateTime('@' . strtotime('now'));
        }

        try {
            $query = new DailyEntriesQuery($dateTime, $this->getAuthenticatedUser()->getId());
            $envelope = $this->commandBus->dispatch($query);
            $handledStamp = $envelope->last(HandledStamp::class);

            return $this->render('User/Daily/index.html.twig',
                $handledStamp->getResult()
            );
        } catch (\Exception $e) {
            return $this->render('User/Daily/index.html.twig');
        }
    }

    private function getAuthenticatedUser(): User
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Authenticated user is required to manage meal entries.');
        }

        return $user;
    }

    private function extractProductFromEntry(Entry $entry): Product
    {
        $product = $entry->getFood()?->first();

        if (!$product instanceof Product) {
            throw new \RuntimeException('Meal entry does not contain a food product.');
        }

        return $product;
    }
}
