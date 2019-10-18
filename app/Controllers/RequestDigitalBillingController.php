<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class RequestDigitalBillingController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'request-digital-billing.twig', [
            'title' => $this->c->lang->label()['request_digital_billing'],
        ]);
    }

    public function postDigitalBilling (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();

        if (!isset($postData['question_options'])){
            $this->c->flash->addMessage('error', 'Please confirm if you would like to use digital billing.');
            return $response->withRedirect($this->c->router->pathFor('get.request.digital.billing'));
        }

        if ($postData['email1'] === "" && $postData['email2'] === "") {
            $this->c->flash->addMessage('error', 'Please enter at least one email address.');
            return $response->withRedirect($this->c->router->pathFor('get.request.digital.billing'));
        }

        
        $data = "";
        $data .= "Customer username" . "," . "Email 1" . "," . "Email 2" . "," . "Created at\n";
        $data .= $_SESSION['customer_number'] . ',' . $postData['email1'] . ',' . $postData['email2'] . ',' . date('Y-m-d H:i:s');

        $file_name = $_SESSION['customer_number'] . '_' . date('ymd_His');
        $filename = "digital_billing/$file_name.csv";
        file_put_contents($filename, $data);
        chmod('digital_billing', 0777);
        $this->c->flash->addMessage('info', 'Request has been submitted.');
        return $response->withRedirect($this->c->router->pathFor('get.request.digital.billing'));
    }
}