<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\ClientReservationType;
use App\Form\SearchRegionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use App\Form\ReservationType;

class FrontOfficeController extends AbstractController
{
    /**
     * @Route("/", name="front_office")
     */
    public function index()
    {
        return $this->render('front_office/index.html.twig', [
            'rooms' => $this->getDoctrine()->getRepository(Room::class)->findAll(),
        ]);
    }

    /**
     * @Route("/room/{id}", name="room_details")
     * @param $id
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function show($id, Request $request, SessionInterface $session)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if (!$room) {
            throw $this->createNotFoundException(
                'No room found for id ' . $id
            );
        }

        if ($request->isXmlHttpRequest()) {
            $bookmarks = $session->get('bookmarks') ?? [];
            if (in_array($id, $bookmarks)) {
                unset($bookmarks[array_search($id, $bookmarks)]);
            } else {
                array_push($bookmarks, $id);
            }
            $session->set('bookmarks', $bookmarks);

            return new Response();
        }

        return $this->render('front_office/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/by-region", name="by_region")
     * @param Request $request
     * @return Response
     */
    public function byRegion(Request $request)
    {
        $form = $this->createForm(SearchRegionType::class);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $region = $form->get('region')->getData();
                return $this->render('front_office/_rooms.html.twig', [
                    'rooms' => $region->getRooms(),
                ]);
            }
        }

        return $this->render('front_office/by_region.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bookmarks", name="bookmarks")
     * @param SessionInterface $session
     * @return Response
     */
    public function bookmarks(SessionInterface $session)
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findBy(
            ['id' => $session->get('bookmarks')]
        );

        return $this->render('front_office/bookmarks.html.twig', [
            'rooms' => $rooms,
        ]);
    }
    
    
    /**
     *
     * @Route("/client/{id}", name="client_reservation")
     * @param $id
     */
    public function showReservation($id)
    {
        $this->denyAccessUnlessGranted("ROLE_CLIENT");
        $user = $this->getUser();
        $client = $user->getClient();
        
        $reservations = $client->getReservations();
        
        if (! $reservations) {
            throw $this->createNotFoundException('No reservation found for id ' . $id);
        }
        
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations
        ]);
    }
    
    /**
     * @Route("/newClient", name="reservation_newClient", methods={"GET","POST"})
     */
    public function newClient(Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_CLIENT");
        $user = $this->getUser();
        $client = $user->getClient();
        $reservation = new Reservation();
        $form = $this->createForm(ClientReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            
            return $this->redirectToRoute('front_office');
        }
        
        return $this->render('front_office/newClient.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    
    
    
}
