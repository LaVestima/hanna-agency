<?php

namespace App\Entity;

use App\Model\Infrastructure\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductImages
 *
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="Product_Image_File_Path_U", columns={"file_path"}),
 *     @ORM\UniqueConstraint(name="Product_Image_Product_Sequence_Position_U", columns={"product_id", "sequence_position"})
 * }, indexes={
 *     @ORM\Index(name="Product_Image_Product_FK", columns={"product_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ProductImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProductImage implements EntityInterface
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
     * @ORM\Column(type="string", length=512, nullable=false)
     *
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
     */
    private $filePath;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $sequencePosition;

    private $file;

    private $tempFilename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productImages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    // TODO: finish file upload
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        if (null !== $this->filePath) {
            $this->tempFilename = $this->filePath;

            $this->filePath = null;
//            $this->alt = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // If there is no file (optional field), nothing is done
        if (null === $this->file) {
            return;
        }

        // The name of the file is its id, one should just store also its extension
        // To make it clean, we should rename this attribute to "extension" rather than "url"
        $this->filePath = $this->file->guessExtension();

        // And we generate the alt attribute of the <img> tag, the value of the file name on the user's PC
//        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // If we had an old file, we delete it
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempFilename;

            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // We move the sent file to the directory of our choice
        $this->file->move(
            $this->getUploadRootDir(), // The destination directory
            $this->id . '.' . $this->filePath   // The name of the file to create, here "extension_id"
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->filePath;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        return 'uploads/images';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../public/'.$this->getUploadDir();
    }


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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
