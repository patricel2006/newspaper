<?php

namespace App\Controller\Admin; //chemin relatif et le src est transformé en App

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin')] // préfixe toutes les routes de la classe
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(): Response
    {
        try {
            // si le role n'est pas admin, je vais dans le catch
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        } catch (AccessDeniedException $exception) {
            // attrape l'exception et "cache" l'erreur et exécute le code de la redirection
            dd($exception->getMessage() . 'dans le fichier : ' . __FILE__ . ' à la ligne ' . __LINE__ . '.');
            return $this->redirectToRoute('show_home');
        }
        return $this->render('admin/show_dashboard.html.twig');
    } //end: showDashboard
}//end: AdminController
