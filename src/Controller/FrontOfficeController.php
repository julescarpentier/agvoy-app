<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return Response
     */
    public function show($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if (!$room) {
            throw $this->createNotFoundException(
                'No room found for id ' . $id
            );
        }

        return $this->render('front_office/show.html.twig', [
            'room' => $room,
        ]);
    }
}
