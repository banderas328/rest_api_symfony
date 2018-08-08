<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;


class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/user"))
     */
    public function getAction(Request $request)
    {
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        return $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array(), null, $limit, $offset);
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function idAction($id)
    {
        $singleResult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleResult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleResult;
    }

    /**
     * @Rest\Post("/user/")
     */
    public function postAction(Request $request)
    {
        $user = new User;
        $firstName = $request->get('first_name');
        $secondName = $request->get('second_name');
        $email = $request->get('email');
        $user->setFirstName($firstName);
        $user->setSecondName($secondName);
        $user->setEmail($email);
        $userExists = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array("email" => $email));
        if ($userExists !== null) {
            return new View("user with such email already exists", Response::HTTP_BAD_REQUEST);
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new View($errorsString, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new View("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateAction($id, Request $request)
    {
        $firstName = $request->get('first_name');
        $secondName = $request->get('second_name');
        $email = $request->get('email');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        $user->setFirstName($firstName);
        $user->setSecondName($secondName);
        $user->setEmail($email);
        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new View($errorsString, Response::HTTP_BAD_REQUEST);
        }
        $sn->flush();
        return new View("User Updated Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }


}