<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    /**
     * @Route("/hello/{numb}", name="number", requirements={"numb"="\d+"})
     */
    public function number($numb) {
        return new Response("Votre chiffre est le " . $numb);
    }

    /**
     * @Route("/hello/{prenom}", name="hello")
     * @Route("/hello")
     * Page qui dit bonjour
     */
    public function hello($prenom = "default") {
        return new Response("Bonjour " . $prenom . " ça va ?");
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home() {
        $jambon = ['Serano' => 12, 'Bayonne' => 8, 'Roupsard' => 24, 'Tamere' => 72];

        return $this->render(
            'home.html.twig', [
                'title' => 'Ouesh',
                'tableau' => $jambon,
            ]
        );
    }

}

?>