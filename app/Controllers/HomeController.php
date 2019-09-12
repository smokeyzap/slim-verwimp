<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class HomeController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        //send ($subject, $toEmail, $toName, $content)
        // $this->c->mailer->send(
        //     'Welcome to ' . $this->c->lang->label()['vms_site_title'],
        //     $postData['email_address'],
        //     $postData['first_name'],
        //     $this->c->view->fetch('email/welcome.twig', ['name' => $postData['first_name']])
        // );
        return $this->c->view->render($response, 'index.twig', [
            'title' => $this->c->lang->label()['app_title'],
        ]);
    }
}