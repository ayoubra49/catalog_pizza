<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PizzaRepository::class)
 */
class Pizza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=PizzaIngredient::class, mappedBy="pizza", cascade={"persist"})
     */
    private $pizzaIngredients;

    public function __construct()
    {
        $this->pizzaIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPizzaIngredients(): ArrayCollection
    {
        return new ArrayCollection($this->pizzaIngredients->toArray());
    }

    public function getTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->pizzaIngredients as $pizzaIngredient) {
            $totalPrice += $pizzaIngredient->getIngredient()->getPrice() * $pizzaIngredient->getQuantity();
        }
        return $totalPrice * 1.5;
    }
    public function getIngredients(): ArrayCollection
    {
        $ingredients = new ArrayCollection();
        foreach ($this->pizzaIngredients as $pizzaIngredient) {
            $ingredients[] = $pizzaIngredient->getIngredient();
        }
        return $ingredients;
    }

}
