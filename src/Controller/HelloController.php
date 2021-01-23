<?php 
namespace App\Controller;

use Twig\Environment;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{

protected $calculer;

public function __construct(Calculator $calculer)
{
  $this->calculator = $calculer;
}


  /**
  * @Route("/hello/{nom?world}" , name="helloName", methods={"GET", "POST"}, host = "127.0.0.1" , schemes={"http" , "https" })
  */
    public function test($nom = "world" , LoggerInterface $logger,Slugify $slugify , Environment $twig ){
      dump($twig);
      dump($slugify ->slugify("Hello world"));
      $logger->info("Mon message de log");
      $tva = $this->calculator->calcul(100);
      dump($tva);

 return new Response("Hello $nom");
    }
}



