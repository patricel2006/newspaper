<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterFormType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());
            // le role nous permettra de distinguer les admins des users
            $user->setRoles(["ROLE_USER"]);

            // il faut hasher le password
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            //remplace $entityManager et fait un persist()
            $repository->save($user, true);

            $this->addFlash('success', "Inscription réussie !");

            return $this->redirectToRoute('app_login');
        }


        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    } // end register()
}// end class RegisterController
