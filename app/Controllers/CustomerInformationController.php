<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class CustomerInformationController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $dealers = Dealer::find($_SESSION['id']);
        return $this->c->view->render($response, 'customer-information.twig', [
            'title' => $this->c->lang->label()['customer_info'],
            'user_info' => $dealers,
        ]);
    }

    public function updateCustomerDeliveryEmail (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dealer = Dealer::where('customer_number', $_SESSION['customer_number'])->first();


        $dealer->email = $postData['delivery_email'];
        $dealer->save();

        $this->c->flash->addMessage('info', 'Delivery note email updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.customer.information'));
    }

    public function updateCustomerInvoiceEmail (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dealer = Dealer::where('customer_number', $_SESSION['customer_number'])->first();


        $dealer->invoice_email = $postData['invoice_email'];
        $dealer->save();

        $this->c->flash->addMessage('info', 'Invoice email updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.customer.information'));
    }

    public function updateCustomerBackorder (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dealer = Dealer::where('customer_number', $_SESSION['customer_number'])->first();


        $dealer->backorder = $postData['backorders_option'];
        $dealer->save();

        $this->c->flash->addMessage('info', 'Backorder option has been updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.customer.information'));
    }
}