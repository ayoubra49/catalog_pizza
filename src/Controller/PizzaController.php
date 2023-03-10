<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use App\Entity\PizzaIngredient;
use App\Form\PizzaType;
use App\Repository\PizzaIngredientRepository;
use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PizzaController extends AbstractController
{
    /**
     * @Route("/pizzas", name="pizza_index", methods={"GET"})
     */
    public function index(PizzaRepository $pizzaRepository, PizzaIngredientRepository $pizzaIngredientRepository): Response
    {
        $pizzas = $pizzaRepository->findAll();
        $pizzaIngredients = [];
        foreach ($pizzas as $pizza) {
            $ingredientsData = $pizzaIngredientRepository->findIngredientByPizzaId($pizza->getId());
            $ingredients = [];
            foreach ($ingredientsData as $ingredientData) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientData['name']);
                $ingredient->setPrice($ingredientData['price']);
                $ingredients[] = $ingredient;
            }
            $pizzaIngredientsCollection = new ArrayCollection($ingredients);
            $pizza->setPizzaIngredients($pizzaIngredientsCollection);
        }
        $form = $this->createFormBuilder()
            ->add('id', HiddenType::class)
            ->getForm();
        return $this->render('pizza/index.html.twig', [
            'pizzas' => $pizzas,
            'delete_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pizzas/new", name="pizza_new", methods={"GET","POST"})
     */
    public function new(Request $request, PizzaIngredientRepository $pizzaIngredientRepository): Response
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData()->getPizzaIngredients();
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($ingredients as $ingredient) {
                $pizzaIngredient = new PizzaIngredient();
                $pizzaIngredient->setPizza($pizza);
                $pizzaIngredient->setIngredient($ingredient);
                $entityManager->persist($pizzaIngredient);
            }

            $entityManager->persist($pizza);
            $entityManager->flush();

            return $this->redirectToRoute('pizza_index');
        }

        return $this->render('pizza/new.html.twig', [
            'pizza' => $pizza,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pizzas/{id}/edit", name="pizza_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pizza $pizza): Response
    {
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData()->getPizzaIngredients();
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($ingredients as $ingredient) {
                $pizzaIngredient = new PizzaIngredient();
                $pizzaIngredient->setPizza($pizza);
                $pizzaIngredient->setIngredient($ingredient);
                $entityManager->persist($pizzaIngredient);
            }

            $entityManager->persist($pizza);
            $entityManager->flush();

            return $this->redirectToRoute('pizza_index');
        }

        return $this->render('pizza/edit.html.twig', [
            'pizza' => $pizza,
            'form' => $form->createView(),
            'delete_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pizzas/{id}", name="pizza_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pizza $pizza): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizza->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pizza);
            $entityManager->flush();
        }
        $this->addFlash('success', 'L\'ingr??dient a ??t?? ajout?? avec succ??s ?? la pizza.');

        return $this->redirectToRoute('show_pizza', ['id' => $pizza->getId()]);
    }

    /**
     * @Route("/pizza/{id}/add-ingredient/{ingredientId}", name="add_ingredient_to_pizza", methods={"POST"})
     */
    public function addIngredientToPizza(Request $request, EntityManagerInterface $entityManager, Pizza $pizza, Ingredient $ingredient): Response
    {
        $quantity = $request->request->get('quantity');
        $pizzaIngredient = new PizzaIngredient();
        $pizzaIngredient->setPizza($pizza);
        $pizzaIngredient->setIngredient($ingredient);
        $pizzaIngredient->setQuantity($quantity);

        $entityManager->persist($pizzaIngredient);
        $entityManager->flush();

        return $this->redirectToRoute('pizza_show', ['id' => $pizza->getId()]);
    }

    /**
     * @Route("/pizza/{id}/remove-ingredient/{pizzaIngredientId}", name="remove_ingredient_from_pizza", methods={"POST"})
     */
    public function removeIngredientFromPizza(EntityManagerInterface $entityManager, Pizza $pizza, PizzaIngredient $pizzaIngredient): Response
    {
        $entityManager->remove($pizzaIngredient);
        $entityManager->flush();

        return $this->redirectToRoute('pizza_show', ['id' => $pizza->getId()]);
    }

    /**
     * @Route("/pizzas/{id}", name="pizza_show", methods={"GET"})
     */
    public function show(Pizza $pizza): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $pizzaIngredients = $entityManager->getRepository(PizzaIngredient::class)->findByPizzaId($pizza->getId());

        // Afficher un message flash s'il y en a un
        $successMessage = $this->get('session')->getFlashBag()->get('success');

        return $this->render('pizza/show.html.twig', [
            'pizza' => $pizza,
            'pizzaIngredients' => $pizzaIngredients,
            //'successMessage' => $successMessage,
        ]);
    }

}