<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BackOfficeController
 * @package App\Controller
 *
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class BackOfficeController extends AbstractController
{
    /**
     * @Route("/", name="back_office")
     */
    public function index()
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }
}
