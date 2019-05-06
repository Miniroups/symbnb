<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller; // ?? Deprecated ??

class HomeController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo) {
        return $this->render('home.html.twig', [
            'ads' => $adRepo->findBestAds(),
            'users' => $userRepo->findBestUsers()
        ]);
    }

}

?>