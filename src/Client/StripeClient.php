<?php

namespace App\Client;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class StripeClient
{
    private $config;
    private $em;
    private $logger;

    public function __construct($secretKey, array $config, EntityManagerInterface $em, LoggerInterface $logger)
    {
        \Stripe\Stripe::setApiKey($secretKey);
        $this->config = $config;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function createCharge($orderCode, User $user, $amount, $token)
    {
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $this->config['decimal'] ? $amount * 100 : $amount,
                'currency' => $this->config['currency'],
                'description' => 'Order ' . $orderCode,
                'source' => $token,
//                'receipt_email' => $user->getEmail(),
                'receipt_email' => 'szymon.grabia@gmail.com',
                'metadata' => [
//                    'order_id' => 2 // TODO: change
                ]
            ]);
        } catch (\Stripe\Error\Base $e) {
            $this->logger->error(
                sprintf(
                    '%s exception encountered when creating a premium payment: "%s"',
                    get_class($e),
                    $e->getMessage()
                ), [
                    'exception' => $e
                ]
            );
            throw $e;
        }
//        $user->setChargeId($charge->id);
//        $user->setPremium($charge->paid);
//        $this->em->flush();
    }
}
