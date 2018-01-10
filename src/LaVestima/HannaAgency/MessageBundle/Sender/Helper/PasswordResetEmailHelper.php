<?php

namespace LaVestima\HannaAgency\MessageBundle\Sender\Helper;

use LaVestima\HannaAgency\AccessControlBundle\Controller\Crud\TokenCrudControllerInterface;
use LaVestima\HannaAgency\AccessControlBundle\Entity\Tokens;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use RandomLib\Factory;

class PasswordResetEmailHelper
{
    private $userCrudController;
    private $tokenCrudController;

    private $user;

    /**
     * PasswordResetEmailHelper constructor.
     *
     * @param UserCrudControllerInterface $userCrudController
     * @param TokenCrudControllerInterface $tokenCrudController
     */
    public function __construct(
        UserCrudControllerInterface $userCrudController,
        TokenCrudControllerInterface $tokenCrudController
    ) {
        $this->userCrudController = $userCrudController;
        $this->tokenCrudController = $tokenCrudController;
    }

    /**
     * Create Password Reset Token in DB.
     *
     * @return string
     * @throws \Exception
     */
    public function createToken()
    {
        if (!$this->user) {
            throw new \Exception('No user defined!');
        }

        $newPasswordToken = $this->generateToken();

        $token = new Tokens();
        $token->setIdUsers($this->user);
        $token->setToken($newPasswordToken);

        $this->tokenCrudController
            ->createEntity($token);

        return $newPasswordToken;
    }

    /**
     * Generate Password Reset Token.
     *
     * @return string
     */
    private function generateToken()
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $activationToken = $generator->generateString(
            100,
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        );

        return $activationToken;
    }

    /**
     * Set User.
     *
     * @param Users $user
     *
     * @return $this
     */
    public function setUser(Users $user)
    {
        $this->user = $user;

        return $this;
    }
}
