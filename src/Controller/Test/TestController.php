<?php

namespace App\Controller\Test;

use App\Client\StripeClient;
use App\Controller\Infrastructure\BaseController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestController extends BaseController
{
    private $stripeClient;

    public function __construct(StripeClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
    }

    /**
     * @Route("/test", name="test_test")
     */
    public function test(Request $request)
    {
//        return $this->render('Test/test.html.twig');

        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
//            var_dump($form);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                var_dump('c');
                try {
                    var_dump('b');
                    $this->stripeClient->createPremiumCharge($this->getUser(), $form->get('token')->getData());
                    $redirect = $this->get('session')->get('premium_redirect');
                } catch (\Stripe\Error\Base $e) {
                    var_dump($e);
                    $this->addFlash('warning', sprintf('Unable to take payment, %s', $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.'));
                    $redirect = $this->generateUrl('test_test');
                } finally {
                    echo 'OK';
                    return $this->redirect($redirect);
                }
            }
            die;
        }

        return $this->render('Test/test.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }
}
