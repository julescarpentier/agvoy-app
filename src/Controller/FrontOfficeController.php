<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Owner;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ClientPublicType;
use App\Form\CommentairePublicType;
use App\Form\OwnerPublicType;
use App\Form\ReservationPublicType;
use App\Form\RoomPublicType;
use App\Form\SelectRegionFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;

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
        $owner = $this->getUser()->getOwner() ?? new Owner();
        $room = new Room();

        $room->setOwner($owner);

        if ($this->isGranted("ROLE_OWNER")) {
            $form = $this->createForm(RoomPublicType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($room);
                $entityManager->flush();

                $this->addFlash('success', "Annonce ajoutée avec succès");

                return $this->redirectToRoute('my_rooms');
            }

            return $this->render('front_office/room_new.html.twig', [
                'room' => $room,
                'form' => $form->createView(),
            ]);
        } else {
            $form = $this->createFormBuilder()
                ->add('owner', OwnerPublicType::class)
                ->add('room', RoomPublicType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getUser()->setOwner($owner);
                $this->getUser()->addRole("ROLE_OWNER");

                $owner = $form->get('owner')->getData();
                $room = $form->get('room')->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($owner);
                $entityManager->persist($room);
                $entityManager->flush();

                $this->addFlash('success', "Annonce ajoutée avec succès, un profil Owner a été créé");

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
     * @Route("/room/{id}/book", name="room_book", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @param Request $request
     * @param Room $room
     * @return Response
     */
    public function roomBook(Request $request, Room $room): Response
    {
        $client = $this->getUser()->getClient() ?? new Client();
        $reservation = new Reservation();

        $reservation->addRoom($room);
        $reservation->setClient($client);

        if ($this->isGranted("ROLE_CLIENT")) {
            $form = $this->createForm(ReservationPublicType::class, $reservation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', "Annonce réservée avec succès");

                return $this->redirectToRoute('my_reservations');
            }
        } else {
            $form = $this->createFormBuilder()
                // ->add('client', ClientPublicType::class) // Ce formulaire est vide compte tenu des infomations demandées pour un Client
                ->add('reservation', ReservationPublicType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getUser()->setClient($client);
                $this->getUser()->addRole("ROLE_CLIENT");

                // $client = $form->get('client')->getData();
                $reservation = $form->get('reservation')->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($client);
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', "Annonce réservée avec succès, un profil Client a été créé");

                return $this->redirectToRoute('my_reservations');
            }
        }

        return $this->render('front_office/room_book.html.twig', [
            'room' => $room,
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/room/{id}/edit", name="public_room_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @param Room $room
     * @return Response
     */
    public function roomEdit(Request $request, Room $room): Response
    {
        if ($room->getOwner()->getId() == $this->getUser()->getOwner()->getId()) {
            $form = $this->createForm(RoomPublicType::class, $room);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', "Annonce éditée avec succès");

                return $this->redirectToRoute('public_room_show', ['id' => $room->getId()]);
            }

            return $this->render('front_office/room_edit.html.twig', [
                'room' => $room,
                'form' => $form->createView(),
            ]);
        } else {
            $this->addFlash('danger', "Vous ne pouvez pas éditer cette annonce");

            return $this->redirectToRoute('front_office');
        }
    }
    
    /**
     * @Route("/room/{id}/comment", name="public_room_comment", methods={"GET", "POST"})
     * @IsGranted("ROLE_CLIENT")
     * @param Request $request
     * @param Room $room
     * @return Response
     */
    
    public function roomComment(Request $request, Room $room): Response        
        {
            $client = $this->getUser()->getClient();
            $commentaire = new Commentaire();
            
            $commentaire->setRoom($room);
            
            $commentaire->setAuteur($client);
           
            $form = $this->createForm(CommentairePublicType::class, $commentaire);
                $form->handleRequest($request);
                
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($commentaire);
                    $entityManager->flush();
                    
                    $this->addFlash('success', "Commentaire ajouté avec succès");
                    
                    return $this->redirectToRoute('my_comments');
                }
            
            return $this->render('front_office/room_comment.html.twig', [
                'room' => $room,
                'commentaire' => $commentaire,
                'form' => $form->createView(),
            ]);
        }
        
    
    
    
    

    /**
     * @Route("/room/{id}", name="public_room_delete", methods={"DELETE"})
     * @IsGranted("ROLE_OWNER")
     * @param Request $request
     * @param Room $room
     * @return Response
     */
    
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete' . $room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();

            $this->addFlash('success', "Annonce supprimée avec succès");
        }

        return $this->redirectToRoute('my_rooms');
    }

    /**
     * @Route("/by-region", name="by_region", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function byRegion(Request $request): Response
    {
        $form = $this->createForm(SelectRegionFormType::class);
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
     * @Route("/my-comments", name="my_comments")
     * @IsGranted("ROLE_CLIENT")
     * @return Response
     */
    public function myComments(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $this->getUser()->getClient()->getCommentaires(),
        ]);
    }

    
    /**
     * @Route("/room/{id}/comments", name="room_comments")
     * @param Room $room
     * @return Response
     */
    public function roomComments(Room $room): Response
    {
        return $this->render('front_office/room_all_comments.html.twig', [
            'commentaires' => $this->getDoctrine()->getRepository(Commentaire::class)->findBy([ 'room' => $room]),
        ]);
    }
}
