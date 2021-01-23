<?php 
namespace App\Controller;

use Twig\Environment;
use App\Taxes\Detector;
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
  * @Route("/hello/{prenom?world}" , name="helloName", methods={"GET", "POST"}, host = "127.0.0.1" , schemes={"http" , "https" })
  */
    public function test($prenom = "magali" , Environment $twig ){

      $html = $twig->render('hello.html.twig', [
'prenom' => $prenom,


      ]);
 return new Response("$html");
    }
}



