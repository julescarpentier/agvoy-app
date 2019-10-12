<?php

namespace App\Controller;

use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    /**
     * @Route("/room/list", name="room_list")
     */
    public function index()
    {
        return $this->render('room/index.html.twig', [
            'rooms' => $this->getDoctrine()->getRepository(Room::class)->findAll(),
        ]);
    }

    /**
     * @Route("/room/{id}", name="room_show")
     */
    public function show($id)
    {
        $room = $this->getDoctrine()->getRepository(Room::class)->find($id);

        if (!$room) {
            throw $this->createNotFoundException(
                'No room found for id ' . $id
            );
        }

        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }
}
