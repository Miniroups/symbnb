<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Permets d'administrer les réservations 
     * 
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     * 
     * @return Response
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);

        return $this->render('admin/bookings/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permets d'éditer une réservations
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking); // variable car formulaire pré rempli !

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/bookings/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permets de supprimer une réservation
     * 
     * @Route("admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );

        return $this->redirectToRoute("admin_booking_index");
    }
}
