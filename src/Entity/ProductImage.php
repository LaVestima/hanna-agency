<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductImages
 *
 * @ORM\Table(name="Product_Images", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Product_Images_File_Path_U", columns={"File_Path"}),
 *     @ORM\UniqueConstraint(name="Product_Images_Products_Position_U", columns={"ID_PRODUCTS", "Sequence_Position"})
 * }, indexes={
 *     @ORM\Index(name="Product_Images_ID_PRODUCTS_FK", columns={"ID_PRODUCTS"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductImageRepository")
 */
class ProductImage implements EntityInterface
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
     * @var string
     *
     * @ORM\Column(name="File_Path", type="string", length=50, nullable=false)
     *
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
     */

    private $filePath;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;

    /**
     * @var integer
     *
     * @ORM\Column(name="Sequence_Position", type="integer")
     */
    private $sequencePosition;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ProductImage
     */
    public function setId($id): ProductImage
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     * @return ProductImage
     */
    public function setFilePath($filePath): ProductImage
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }

    /**
     * @param mixed $idProducts
     * @return ProductImage
     */
    public function setIdProducts($idProducts): ProductImage
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSequencePosition()
    {
        return $this->sequencePosition;
    }

    /**
     * @param mixed $sequencePosition
     * @return ProductImage
     */
    public function setSequencePosition($sequencePosition): ProductImage
    {
        $this->sequencePosition = $sequencePosition;

        return $this;
    }
}
