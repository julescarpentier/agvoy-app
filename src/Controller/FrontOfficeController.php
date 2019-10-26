<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\SearchRegionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/by-region", name="by_region")
     * @param Request $request
     * @return Response
     */
    public function regions(Request $request)
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

        return $this->render('front_office/regions.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
