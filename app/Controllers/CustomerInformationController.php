<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;
use App\Models\OpenHour;

class CustomerInformationController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $dealers = Dealer::find($_SESSION['id']);
        $opening_hours = OpenHour::where('customer_number', $_SESSION['customer_number'])->get();
        
        return $this->c->view->render($response, 'customer-information.twig', [
            'title' => $this->c->lang->label()['customer_info'],
            'user_info' => $dealers,
            'opening_hours' => $opening_hours,
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

    public function updateOpeningHours (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $time = [];
        foreach ($postData['days'] as $key => $value) {
            $time[$key] = str_replace(' ','',explode('-', $value));
        }
        
        foreach ($time as $id => $value) {

            $t = OpenHour::find($id);
            $t->open_from = $value[0];
            $t->open_until = $value[1];
            $t->closed = 0;
            $t->save();
        }

        if (isset($postData['is_closed'])) {
            foreach ($postData['is_closed'] as $id => $value) {
                $c = OpenHour::find($id);
                $c->open_from = "";
                $c->open_until = "";
                $c->closed = 1;
                $c->save();
            }
        }

        $this->c->flash->addMessage('info', 'Open hours has been updated.');
        return $response->withRedirect($this->c->router->pathFor('get.customer.information'));
    }
}