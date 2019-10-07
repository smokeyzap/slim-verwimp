<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;
class QuestionController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'ask_question.twig', [
            'title' => $this->c->lang->label()['ask_question'],
        ]);
    }

    public function postQuestion (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        
        if ($postData['question_options'] == 'Other' && $postData['others'] === "") {
            $this->c->flash->addMessage('error', 'Please type your other concern in the text field.');
            return $response->withRedirect($this->c->router->pathFor('get.ask.question'));
        }
        $dealer = Dealer::find($_SESSION['id']);
        
        //send ($subject, $toEmail, $toName, $content)
        $this->c->mailer->send(
            $this->c->lang->label()['app_title'] . ' - ' . $this->c->lang->label()['ask_question'],
            $dealer->email,
            $dealer->name,
            $this->c->view->fetch('email/ask_question.twig', ['name' => $dealer->name])
        );

        $this->c->flash->addMessage('info', 'Your question has been sent.');
        return $response->withRedirect($this->c->router->pathFor('get.ask.question'));
    }
}