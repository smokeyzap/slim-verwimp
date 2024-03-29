<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Eticket;

class LabelDimensionController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $labels = Eticket::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'label-dimension.twig', [
            'title' => $this->c->lang->label()['label_dimension_label'],
            'data' => $labels,
        ]);
    }

    public function addDimension (Request $request, Response $response)
    {
        return $this->c->view->render($response, 'add-label-dimension.twig', [
            'title' => $this->c->lang->label()['add_label'] . ' ' . $this->c->lang->label()['label_dimension_label'],
        ]);
    }

    public function postLabelDimension (Request $request, Response $response) 
    {
        $postData = $request->getParsedBody();
        $label = Eticket::create([
            'customer_number' => $_SESSION['customer_number'],
            'name' => $postData['name'],
            'eticket_left' => $postData['left'],
            'eticket_right' => $postData['right'],
            'above' => $postData['up'],
            'below' => $postData['down'],
            'number_for_columns' => $postData['column'],
            'spacing' => $postData['space'],
            'height' => $postData['height'],
        ]);

        $this->c->flash->addMessage('info', 'New label dimension has been added successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.label.dimension'));

    }

    public function editLabelDimension (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $label = Eticket::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();

        if (!$label) {
            die('Access denied');
        }

        return $this->c->view->render($response, 'edit-label-dimension.twig', [
            'title' => $this->c->lang->label()['edit'] . ' ' . $this->c->lang->label()['label_dimension_label'],
            'data' => $label,
        ]);
    }

    public function updateLabelDimension (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $label = Eticket::where('id', $postData['id'])
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (!$label) {
            die('Access denied');
        }

        $label->name = $postData['name'];
        $label->eticket_left = $postData['left'];
        $label->eticket_right = $postData['right'];
        $label->above = $postData['up'];
        $label->below = $postData['down'];
        $label->number_for_columns = $postData['column'];
        $label->spacing = $postData['space'];
        $label->height = $postData['height'];
        $label->save();

        $this->c->flash->addMessage('info', 'Updated successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.label.dimension'));
    }   

    public function deleteLabelDimension (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $label = Eticket::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->delete();

        $this->c->flash->addMessage('info', 'Label dimension deleted successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.label.dimension'));
    }
}