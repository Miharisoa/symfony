<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;

class CrawlingController extends AbstractController
{
    /**
     * @Route("/crawling", name="crawling")
     */
    public function index()
    {

        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <p class="message">Hello world</p>
    <p>hello Crawler!</p>
    <p>Hahaha</p>
    <div> <p>Salut oh</p></div>
</body>
</html>
HTML;


        $crawler = new Crawler($html);

        foreach ($crawler as $domElement) {
            var_dump($domElement->nodeName);
            var_dump($domElement->textContent);
        }
        return $this->render('crawling/index.html.twig', [
            'controller_name' => 'CrawlingController',
        ]);
    }

}
