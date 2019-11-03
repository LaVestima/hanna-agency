<?php

namespace App\Controller\Store;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Role;
use App\Entity\StoreSubuser;
use App\Form\StoreSubuserSettingsType;
use App\Repository\RoleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subuser")
 */
class StoreSubuserController extends BaseController
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @Route("s", name="store_subuser_list")
     *
     * @IsGranted("ROLE_STORE_ADMIN")
     */
    public function list()
    {
        $store = $this->getStore();

        return $this->render('Store/StoreSubuser/list.html.twig', [
            'store' => $store,
            'storeRoles' => $this->roleRepository->findBy(['subrole' => true])
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

            return $this->redirectToRoute('store_subuser_list');
        }

        return $this->render('Store/StoreSubuser/settings.html.twig', [
            'subuser' => $storeSubuser,
            'form' => $form->createView(),
            'allSubroles' => $this->getDoctrine()->getRepository(Role::class)->findBy(['subrole' => true])
        ]);
    }
}
