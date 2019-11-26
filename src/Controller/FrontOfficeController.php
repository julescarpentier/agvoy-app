<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ClientReservationType;
use App\Form\NewOwnerFormType;
use App\Form\NewRoomFormType;
use App\Form\SearchRegionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontOfficeController extends AbstractController
{
    /**
     * @Route("/", name="front_office", methods={"GET"})
     * @return Response
     */
    public function frontOffice(): Response
    {
        return $this->render('front_office/index.html.twig', [
            'rooms' => $this->getDoctrine()->getRepository(Room::class)->findAll(),
        ]);
    }

    /**
     * @Route("/room/new", name="public_room_new", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function room_new(Request $request): Response
    {
        if ($this->isGranted("ROLE_OWNER")) {
            $room = new Room();
            $form = $this->createForm(NewRoomFormType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $room->setOwner($this->getUser()->getOwner());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($room);
                $entityManager->flush();

                return $this->redirectToRoute('my_rooms');
            }

            return $this->render('front_office/room_new.html.twig', [
                'room' => $room,
                'form' => $form->createView(),
            ]);
        } else {
            $owner = new Owner();
            $form = $this->createForm(NewOwnerFormType::class, $owner);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getUser()->setOwner($owner);
                $this->getUser()->addRole("ROLE_OWNER");

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($owner);
                $entityManager->flush();

                return $this->redirectToRoute('my_rooms');
            }

            return $this->render('front_office/room_new.html.twig', [
                'owner' => $owner,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/room/{id}", name="public_room_show", methods={"GET", "POST"})
     * @param Room $room
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function roomShow(Room $room, Request $request, SessionInterface $session): Response
    {
        if ($request->isXmlHttpRequest()) {
            $bookmarks = $session->get('bookmarks') ?? [];
            if (in_array($room->getId(), $bookmarks)) {
                unset($bookmarks[array_search($room->getId(), $bookmarks)]);
            } else {
                array_push($bookmarks, $room->getId());
            }
            $session->set('bookmarks', $bookmarks);

            return new Response();
        }

        return $this->render('front_office/room_show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/reservation/new")
     * @param Request $request
     * @return Response
     */
    public function reservationNew(Request $request): Response
    {

        return new Response();
    }

    /**
     * @Route("/by-region", name="by_region", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function byRegion(Request $request): Response
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
     * @Route("/bookmarks", name="bookmarks", methods={"GET"})
     * @param SessionInterface $session
     * @return Response
     */
    public function bookmarks(SessionInterface $session): Response
    {
        $rooms = $this->getDoctrine()->getRepository(Room::class)->findBy(
            ['id' => $session->get('bookmarks')]
        );

        return $this->render('front_office/bookmarks.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/my-rooms", name="my_rooms", methods={"GET"})
     * @IsGranted("ROLE_OWNER")
     * @return Response
     */
    public function myRooms(): Response
    {
        return $this->render('front_office/my_rooms.html.twig', [
            'rooms' => $this->getUser()->getOwner()->getRooms(),
        ]);
    }

    /**
     *
     * @Route("/my-reservations", name="my_reservations")
     * @IsGranted("ROLE_CLIENT")
     * @return Response
     */
    public function myReservations(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $this->getUser()->getClient()->getReservations(),
        ]);
    }

    /**
     * @Route("/newClient", name="reservation_newClient", methods={"GET","POST"})
     * @IsGranted("ROLE_CLIENT")
     * @param Request $request
     * @return Response
     */
    public function newClient(Request $request): Response
    {
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
