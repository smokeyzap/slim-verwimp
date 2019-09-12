<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class AdminController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'admin/index.twig', [
            'title' => $this->c->lang->label()['home'],
        ]);
    }

    public function getSignIn (Request $request, Response $response) {
        return $this->c->view->render($response, 'admin/sign_in.twig', [
            'title' => $this->c->lang->label()['app_title'] . ' - ' . $this->c->lang->label()['admin_login'],
        ]);
    }

    public function postSignIn (Request $request, Response $response) 
    {   
        $dealer = new Dealer;
        $postData = $request->getParsedBody();
        $auth = $dealer->auth($postData['email'], $postData['password']);
        
        if (!$auth) {
            $this->c->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->c->router->pathFor('admin.get.sign.in'));
        } else {
            return $response->withRedirect($this->c->router->pathFor('admin.home'));
        }
    }

    public function getDealers (Request $request, Response $response)
    {
        $dealers = Dealer::orderBy('id', 'desc')->get();
        return $this->c->view->render($response, 'admin/dealers.twig', [
            'title' => $this->c->lang->label()['dealers'],
            'dealers' => $dealers,
        ]);
    }

    public function getAddDealer (Request $request, Response $response) 
    {
        return $this->c->view->render($response, 'admin/add_dealer.twig', [
            'title' => $this->c->lang->label()['add_dealer'],
        ]);   
    }

    public function postAddDealer (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dealer = Dealer::create([
            'customer_number' => $postData['customer_number'],
            'name' => $postData['customer_name'],
            'address' => $postData['customer_address'],
            'zip' => $postData['customer_zip'],
            'country' => $postData['customer_country'],
            'city' => $postData['customer_city'],
            'phone' => $postData['customer_phone'],
            'fax' => $postData['customer_fax'],
            'email' => $postData['customer_email'],
            'vat_number' => $postData['customer_vat_number'],
            'password' => password_hash($postData['password1'], PASSWORD_DEFAULT, ['cost'=>12]),
            'is_admin' => $postData['is_admin']
        ]);

        if ($dealer) {
            $this->c->flash->addMessage('info', 'New Dealer has been added successfully.');
            return $response->withRedirect($this->c->router->pathFor('admin.get.add.dealer'));
        }
    }

    public function getEditDealer (Request $request, Response $response) 
    {
        return $this->c->view->render($response, 'admin/edit_dealer.twig', [
            'title' => $this->c->lang->label()['edit_dealer'],
        ]);
    }

    public function getOrders (Request $request, Response $response)
    {
        return $this->c->view->render($response, 'admin/orders.twig', [
            'title' => $this->c->lang->label()['orders'],
        ]);
    }
}