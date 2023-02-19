<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Ingredient;
use App\Entity\PizzaIngredient;
use App\Form\PizzaIngredientType;
use App\Repository\PizzaIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PizzaIngredientController extends AbstractController
{
    /**
     * @Route("/pizza/{id}/add-ingredient", name="pizza_ingredient_add", methods={"GET","POST"})
     */
    public function addIngredient(Request $request, Pizza $pizza): Response
    {
        $pizzaIngredient = new PizzaIngredient();
        $pizzaIngredient->setPizza($pizza);
        $form = $this->createForm(PizzaIngredientType::class, $pizzaIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pizzaIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('pizza_show', ['id' => $pizza->getId()]);
        }

        return $this->render('pizza_ingredient/add.html.twig', [
            'pizza' => $pizza,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pizza-ingredient/{id}", name="pizza_ingredient_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PizzaIngredient $pizzaIngredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizzaIngredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pizzaIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pizza_show', ['id' => $pizzaIngredient->getPizza()->getId()]);
    }
}
