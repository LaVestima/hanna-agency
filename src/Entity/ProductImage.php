<?php

namespace App\Entity;

use App\Helper\RandomHelper;
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

    private $tmpFilePath;

    private $fileName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productImages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(): void
    {
        if (null === $this->file) {
            return;
        }

        $this->fileName = RandomHelper::generateString(64) . '.' . $this->file->guessExtension();
        $this->filePath = $this->getUploadDir() . $this->fileName;
        // TODO: get the correct position
        $this->sequencePosition = random_int(5, 100);

        // And we generate the alt attribute of the <img> tag, the value of the file name on the user's PC
//        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(): void
    {
        var_dump('upload');
        if (null === $this->file) {
            return;
        }

        // If we had an old file, we delete it
//        if (null !== $this->tempFilename) {
//            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempFilename;
//
//            if (file_exists($oldFile)) {
//                unlink($oldFile);
//            }
//        }

        $this->file->move(
            $this->getUploadRootDir() . $this->getUploadDir(),
            $this->fileName
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(): void
    {
        $this->tmpFilePath = $this->filePath;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): void
    {
        if (file_exists($this->tmpFilePath)) {
            unlink($this->tmpFilePath);
        }
    }

    public function getUploadDir(): string
    {
        return 'uploads/images/';
    }

    protected function getUploadRootDir(): string
    {
        return __DIR__ . '/../../public/';
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
