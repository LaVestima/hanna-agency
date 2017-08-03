<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaVestima\HannaAgency\InfrastructureBundle\Model\EntityInterface;

/**
 * UsersSettings
 *
 * @ORM\Table(name="Users_Settings", uniqueConstraints={@ORM\UniqueConstraint(name="Users_Settings_ID_USERS_U", columns={"ID_USERS"})})
 * @ORM\Entity
 */
class UsersSettings implements EntityInterface
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
     * @ORM\Column(name="Locale", type="string", length=2, nullable=false)
     */
    private $locale = 'en';

    /**
     * @var boolean
     *
     * @ORM\Column(name="Newsletter", type="boolean", nullable=false)
     */
    private $newsletter = '1';

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USERS", referencedColumnName="ID")
     * })
     */
    private $idUsers;



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
     * Set locale
     *
     * @param string $locale
     *
     * @return UsersSettings
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return UsersSettings
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set idUsers
     *
     * @param Users $idUsers
     *
     * @return UsersSettings
     */
    public function setIdUsers(Users $idUsers = null)
    {
        $this->idUsers = $idUsers;

        return $this;
    }

    /**
     * Get idUsers
     *
     * @return Users
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }
}
