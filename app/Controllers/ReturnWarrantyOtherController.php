<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Returns;

class ReturnWarrantyOtherController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $returns = Returns::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'return-warranty-other.twig', [
            'title' => $this->c->lang->label()['return_warranty_other'],
            'returns' => $returns,
        ]);
    }

    public function removeWarrantyOther (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $returns = Returns::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (!$returns) {
            $this->c->flash->addMessage('error', 'Something went wrong. Please try again.');
            return $response->withRedirect($this->c->router->pathFor('get.return.warranty.other'));
        }

        $returns->delete();
        $this->c->flash->addMessage('info', 'Removed successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.return.warranty.other'));
    }

    public function editWarrantyOther (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $returns = Returns::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (!$returns) {
            $this->c->flash->addMessage('error', 'Something went wrong. Please try again.');
            return $response->withRedirect($this->c->router->pathFor('get.return.warranty.other'));
        }
        dump($id);
        die();
        return $this->c->view->render($response, 'edit-warranty.twig', [
            'title' => $this->c->lang->label()['return_warranty_label'],
            'returns' => $returns,
        ]);
    }

    public function postNewReturnWarrantyOther (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        dump($postData);
    }
}