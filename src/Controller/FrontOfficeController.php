<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\SearchRegionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontOfficeController extends AbstractController
{
    /**
     * @Route("/", name="front_office")
     */
    public function frontOffice()
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
    public function roomDetails($id, Request $request, SessionInterface $session)
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

    public function room_new()
    {

    }
}
