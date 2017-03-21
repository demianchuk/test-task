<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\FOS\User;
use ApiBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UsersController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of FOS users.
     *
     * @ApiDoc(
     *  section="Users",
     *  resource=true,
     *  description=" Get list of FOS users",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     */
    public function getAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository("AppBundle:FOS\User");
        $users = $repository->findAll();

        $view = $this->view($users, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="Users",
     *  description="Create new FOS User",
     *  input="ApiBundle\Form\UserType",
     *  output="AppBundle\Entity\FOS\User",
     *  statusCodes={
     *         200="Returned when successful",
     *         409="The request could not be completed due to a conflict"
     *  }
     * )
     */
    public function postAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user->setPlainPassword($user->getPassword());
            $userManager->updateUser($user);

            $view = $this->view($user, 200);

            return $this->handleView($view);
        }

        $view = $this->view($form->getErrors(), 409);

        return $this->handleView($view);
    }
}
