<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\VatMargin;

class VatMarginController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $vat_margin = VatMargin::where('customer_number', $_SESSION['customer_number'])->first();
        
        return $this->c->view->render($response, 'vat-margin.twig', [
            'title' => $this->c->lang->label()['vat_margin_label'],
            'vat_margin' => isset($vat_margin->vat_margin)?$vat_margin->vat_margin:0,
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
        
        $vat_margin = VatMargin::where('customer_number', $_SESSION['customer_number'])->first();

        if (!$vat_margin) {
            //if not found create 
            VatMargin::create([
                'customer_number' => $_SESSION['customer_number'],
                'vat_margin' => $postData['vat_margin'],
            ]);
        } else {
            //if found, just update the info
            $vm = VatMargin::where('customer_number', $_SESSION['customer_number'])->first();
            $vm->vat_margin = $postData['vat_margin'];
            $vm->save();
        }

        $this->c->flash->addMessage('info', 'Vat margin has been updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.vat.margin'));
    }
}