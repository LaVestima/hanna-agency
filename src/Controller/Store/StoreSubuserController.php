<?php

namespace App\Controller\Store;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Role;
use App\Entity\StoreSubuser;
use App\Form\StoreSubuserSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subuser")
 */
class StoreSubuserController extends BaseController
{
    /**
     * @Route("s", name="store_subuser_list")
     *
     * @IsGranted("ROLE_STORE_ADMIN")
     */
    public function list()
    {
        $store = $this->getStore();

        return $this->render('Store/StoreSubuser/list.html.twig', [
            'store' => $store
        ]);
    }

    /**
     * @Route("/edit/{identifier}", name="store_subuser_edit")
     *
     * @IsGranted("ROLE_STORE_ADMIN")
     */
    public function subuserSettings(StoreSubuser $storeSubuser, Request $request)
    {
        $form = $this->createForm(StoreSubuserSettingsType::class, $storeSubuser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storeSubuser);
            $em->flush();
        }

        return $this->render('Store/StoreSubuser/settings.html.twig', [
            'subuser' => $storeSubuser,
            'form' => $form->createView(),
            'allSubroles' => $this->getDoctrine()->getRepository(Role::class)->findBy(['subrole' => true])
        ]);
    }
}
