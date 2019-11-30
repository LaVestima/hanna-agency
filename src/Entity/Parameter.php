<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 */
class Parameter implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="parameter", orphanRemoval=true)
     */
    private $productParameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParameterCategory", mappedBy="parameter", orphanRemoval=true)
     */
    private $parameterCategories;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;


    public function __construct()
    {
        $this->productParameters = new ArrayCollection();
        $this->parameterCategories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return Collection|ProductParameter[]
     */
    public function getProductParameters(): Collection
    {
        return $this->productParameters;
    }

    public function addProductParameter(ProductParameter $productParameter): self
    {
        if (!$this->productParameters->contains($productParameter)) {
            $this->productParameters[] = $productParameter;
            $productParameter->setParameter($this);
        }

        return $this;
    }

    public function removeProductParameter(ProductParameter $productParameter): self
    {
        if ($this->productParameters->contains($productParameter)) {
            $this->productParameters->removeElement($productParameter);
            // set the owning side to null (unless already changed)
            if ($productParameter->getParameter() === $this) {
                $productParameter->setParameter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ParameterCategory[]
     */
    public function getParameterCategories(): Collection
    {
        return $this->parameterCategories;
    }

    public function addParameterCategory(ParameterCategory $parameterCategory): self
    {
        if (!$this->parameterCategories->contains($parameterCategory)) {
            $this->parameterCategories[] = $parameterCategory;
            $parameterCategory->setParameter($this);
        }

        return $this;
    }

    public function removeParameterCategory(ParameterCategory $parameterCategory): self
    {
        if ($this->parameterCategories->contains($parameterCategory)) {
            $this->parameterCategories->removeElement($parameterCategory);
            // set the owning side to null (unless already changed)
            if ($parameterCategory->getParameter() === $this) {
                $parameterCategory->setParameter(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
