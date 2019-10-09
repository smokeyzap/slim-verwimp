<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class VatMarginController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'vat-margin.twig', [
            'title' => $this->c->lang->label()['vat_margin_label'],
        ]);
    }

    public function postVatMargin (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $vat = is_numeric($postData['vat_margin']);
        if (!$vat) {
            $this->c->flash->addMessage('error', 'Invalid input.');
            return $response->withRedirect($this->c->router->pathFor('get.vat.margin'));
        }
        dump($postData);
    }
}