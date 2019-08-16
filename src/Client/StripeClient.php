<?php

namespace App\Client;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Stripe\Charge;
use Stripe\Stripe;

class StripeClient
{
    private $config;
    private $logger;

    public function __construct($secretKey, array $config, LoggerInterface $logger)
    {
        Stripe::setApiKey($secretKey);
        $this->config = $config;
        $this->logger = $logger;
    }

    public function createCharge($orderCode, User $user, $amount, $token)
    {
        try {
            Charge::create([
                'amount' => $this->config['decimal'] ? $amount * 100 : $amount,
                'currency' => $this->config['currency'],
                'description' => 'Order ' . $orderCode,
                'source' => $token,
                'receipt_email' => $user->getEmail(),
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
    }
}
