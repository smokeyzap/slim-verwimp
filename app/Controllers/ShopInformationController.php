<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class ShopInformationController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $data = Dealer::find($_SESSION['id']);
        return $this->c->view->render($response, 'shop-information.twig', [
            'title' => $this->c->lang->label()['shop_information'],
            'data' => $data,
        ]);
    }

    public function postShopInformation (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dealer = Dealer::find($_SESSION['id']);

        $dealer->email = $postData['email'];
        $dealer->save();

        $this->c->flash->addMessage('info', 'Updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.shop.information'));
    }
}