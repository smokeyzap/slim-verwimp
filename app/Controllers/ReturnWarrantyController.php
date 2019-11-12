<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\ReturnBikkel;

class ReturnWarrantyController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $returns = ReturnBikkel::where('customer_number', $_SESSION['customer_number'])
                ->orderBy('date_of_purchase', 'desc')    
                ->get();
        return $this->c->view->render($response, 'return-warranty.twig', [
            'title' => $this->c->lang->label()['return_warranty_label'],
            'returns' => $returns,
        ]);
    }

    public function postNewReturnWarranty (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dp = date_create($postData['date_purchase']);
        $returns = ReturnBikkel::create([
            'customer_number' => $_SESSION['customer_number'],
            'item_number' => $postData['item_number'],
            'name' => $postData['item_name'],
            'bicycle_type' => $postData['bike_type'],
            'frame_number' => $postData['frame_number'],
            'accu_number' => $postData['battery_number'],
            'date_of_purchase' => date_format($dp, 'Y-m-d'),
            'registered' => $postData['registered'],
            'defective' => $postData['failure'],
            'defect_code' => $postData['failure_code'],
        ]);

        $this->c->flash->addMessage('info', 'Added successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.return.warranty'));
    }

    public function removeWarranty (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $returns = ReturnBikkel::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (!$returns) {
            $this->c->flash->addMessage('error', 'Something went wrong. Please try again.');
            return $response->withRedirect($this->c->router->pathFor('get.return.warranty'));
        }

        $returns->delete();
        $this->c->flash->addMessage('info', 'Removed successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.return.warranty'));
    }

    public function editWarranty (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $returns = ReturnBikkel::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (!$returns) {
            $this->c->flash->addMessage('error', 'Something went wrong. Please try again.');
            return $response->withRedirect($this->c->router->pathFor('get.return.warranty'));
        }

        return $this->c->view->render($response, 'edit-warranty.twig', [
            'title' => $this->c->lang->label()['return_warranty_label'],
            'returns' => $returns,
        ]);
    }

    public function updateWarranty (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $dp = date_create($postData['date_purchase']);
        $returns = ReturnBikkel::where('id', $postData['id'])->first();
        $returns->item_number = $postData['item_number'];
        $returns->name = $postData['item_name'];
        $returns->bicycle_type = $postData['bike_type'];
        $returns->frame_number = $postData['frame_number'];
        $returns->accu_number = $postData['battery_number'];
        $returns->date_of_purchase = date_format($dp, 'Y-m-d');
        $returns->registered = $postData['registered'];
        $returns->defective = $postData['failure'];
        $returns->defect_code = $postData['failure_code'];
        $returns->save();

        $this->c->flash->addMessage('info', 'Updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.return.warranty'));
    }
}