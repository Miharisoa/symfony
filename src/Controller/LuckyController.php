<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky/{number}", name="lucky_number", requirements={"number"="\d+"})
     * +*/
    public function number(){
        $number = random_int(0, 100);
//        $routeName = $request->attributes->get
        return $this->render(
            'lucky/number.html.twig', [
            'number' => $number,]);
    }
}
