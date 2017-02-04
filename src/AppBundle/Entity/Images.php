<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="Images", uniqueConstraints={@ORM\UniqueConstraint(name="Images_File_PathU", columns={"File_Path"})}, indexes={@ORM\Index(name="FK_E7B3BB5CEC93A684", columns={"ID_PRODUCTS"})})
 * @ORM\Entity
 */
class Images
{
    /**
     * @var string
     *
     * @ORM\Column(name="File_Path", type="string", length=50, nullable=false)
     */
    private $filePath;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Products
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTS", referencedColumnName="ID")
     * })
     */
    private $idProducts;



    /**
     * Set filePath
     *
     * @param string $filePath
     *
     * @return Images
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idProducts
     *
     * @param \AppBundle\Entity\Products $idProducts
     *
     * @return Images
     */
    public function setIdProducts(\AppBundle\Entity\Products $idProducts = null)
    {
        $this->idProducts = $idProducts;

        return $this;
    }

    /**
     * Get idProducts
     *
     * @return \AppBundle\Entity\Products
     */
    public function getIdProducts()
    {
        return $this->idProducts;
    }
}
