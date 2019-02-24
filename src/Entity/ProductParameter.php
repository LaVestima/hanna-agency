<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductsParameters
 *
 * @ORM\Table(name="Products_Parameters", indexes={
 *     @ORM\Index(name="Products_Parameters_ID_PRODUCTS_FK", columns={"ID_PRODUCTS"}),
 *     @ORM\Index(name="Products_Parameters_ID_PARAMETERS_FK", columns={"ID_PARAMETERS"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductParameterRepository")
 */
class ProductParameter implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productParameters", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;

    /**
     * @var Parameter
     *
     * @ORM\ManyToOne(targetEntity="Parameter")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PARAMETERS", referencedColumnName="ID")
     * })
     */
    private $idParameters;

    /**
     * @var string
     *
     * @ORM\Column(name="Value", type="text", nullable=false)
     */
    private $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Product
     */
    public function getIdProducts(): Product
    {
        return $this->idProducts;
    }

    /**
     * @param Product $idProducts
     */
    public function setIdProducts(Product $idProducts): void
    {
        $this->idProducts = $idProducts;
    }

    /**
     * @return Parameter
     */
    public function getIdParameters(): Parameter
    {
        return $this->idParameters;
    }

    /**
     * @param Parameter $idParameters
     */
    public function setIdParameters(Parameter $idParameters): void
    {
        $this->idParameters = $idParameters;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
