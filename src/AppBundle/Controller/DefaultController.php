<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Customers;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/invoices", name="invoices")
     */
    public function invoicesAction() {
        //get data from database
        return $this->render('default/invoices.html.twig'
//            ['db_result' => $dbh->query($query)]
        );
    }

    /**
     * @Route("/product/{urlSlugName}", name="product", defaults={"urlSlugName" = ""})
     * @Route("/product/")
     */
//    public function productAction($urlSlugName) {
//        $product = $this->getDoctrine()
//            ->getRepository('AppBundle:Product')
//            //->find($urlSlugName);
//            ->findOneBy(array(
//                'urlSlugName' => $urlSlugName
//            ));
//        if (!$product) {
//            $this->addFlash(
//                'productListError',
//                'Wrong Product Chosen!'
//            );
//            return $this->redirectToRoute('product_list', array( //TODO: Fix
////                'productListError' => "Wrong product!",
//            ));
//        }
//        return $this->render('default/product.html.twig', array(
//            'product' => $product
//        ));
//    }
}
