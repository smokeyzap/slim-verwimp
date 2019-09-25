<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Completion;

class RoundOffController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $completion = Completion::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'round-off.twig', [
            'title' => $this->c->lang->label()['customer_info'],
            'data' => $completion,
        ]);
    }

    public function deleteRoundOff (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        Completion::where('id', $id)->delete();
        $this->c->flash->addMessage('info', 'Deleted successfully');
        return $response->withRedirect($this->c->router->pathFor('get.round.off'));
    }

    public function editRoundOff (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $data = Completion::find($id);
        
        return $this->c->view->render($response, 'edit-round-off.twig', [
            'title' => $this->c->lang->label()['edit'] . ' ' . $this->c->lang->label()['round_off_label'],
            'data' => $data,
        ]);
        
    }

    public function updateRoundOff (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $completion = Completion::find($postData['id']);
        $completion->completion_from = $postData['from'];
        $completion->completion_until = $postData['to'];
        $completion->completion_precision = $postData['precision'];
        $completion->save();
        $this->c->flash->addMessage('info', 'Updated successfully');
        return $response->withRedirect($this->c->router->pathFor('get.round.off'));
    }

    public function postNewRoundOff (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        
        $round_off = Completion::create([
            'customer_number' => $_SESSION['customer_number'],
            'completion_from' => $postData['from'],
            'completion_until' => $postData['to'],
            'completion_precision' => $postData['precision']
        ]);
        
        $this->c->flash->addMessage('info', 'New round off has been added successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.round.off'));
    }
}