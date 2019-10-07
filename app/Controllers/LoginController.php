<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class LoginController extends Controller
{
    public function postSignIn (Request $request, Response $response) 
    {   
        $dealer = new Dealer;
        $postData = $request->getParsedBody();

        $auth = $dealer->auth($postData['email'], $postData['password']);

        
        if (!$auth) {
            $this->c->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->c->router->pathFor('index'));
        } else {
            return $response->withRedirect($this->c->router->pathFor('get.item.list'));
        }
    }

    public function signout (Request $request, Response $response) 
    {
        $dealer = new Dealer;
        $dealer->signout();
        return $response->withRedirect($this->c->router->pathFor('index'));
    }

    public function forgottenPassword (Request $request, Response $response) 
    {
        return $this->c->view->render($response, 'forgotten-password/forgotten-password.twig', [
            'title' => 'Forgotten password',
        ]);
    }

    public function postForgottenPassword (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();

        if ($postData['email'] === "") {
            $this->c->flash->addMessage('error', 'Please enter your email address.');
            return $response->withRedirect($this->c->router->pathFor('forgotten.password'));
        }

        $dealer = Dealer::where('email', $postData['email'])->first();
        if (!$dealer) {
            $this->c->flash->addMessage('error', 'Could not find that user.');
            return $response->withRedirect($this->c->router->pathFor('forgotten.password'));
        }

        $u = new Dealer;
        $randStr = $u->generateRandomString();

        $dealer->recover_hash = $randStr;
        $dealer->save();

        //send ($subject, $toEmail, $toName, $content)
        $this->c->mailer->send(
            $this->c->lang->label()['app_title'] . ' - Reset password',
            $dealer->email,
            $dealer->name,
            $this->c->view->fetch('email/reset-password-link.twig', ['name' => $dealer->name, 'token'=>$dealer->recover_hash])
        );

        $this->c->flash->addMessage('info', 'Email sent.');
        return $response->withRedirect($this->c->router->pathFor('index'));
    }   

    public function resetPassword (Request $request, Response $response)
    {
        $token = $request->getAttribute('token_id');
        $dealer = Dealer::where('recover_hash', $token)->first();

        if (!$dealer) {
            $this->c->flash->addMessage('error', 'Either you changed your password already or link expired.');
            return $response->withRedirect($this->c->router->pathFor('index'));
        }

        return $this->c->view->render($response, 'forgotten-password/reset-password.twig', [
            'title' => 'Reset password',
            'u_id' => $dealer->id,
            'token' => $token,
        ]);
    }

    public function postNewPassword (Request $request, Response $response)
    {
        $token = $request->getQueryParams('token');
        $postData = $request->getParsedBody();

        if ($postData['password1'] != $postData['password2']) {
            $this->c->flash->addMessage('error', 'Password does not match. Please try again.');
            return $response->withRedirect($this->c->router->pathFor('reset.password', ['token_id' => $token['token']]));
        }

        $dealer = Dealer::where('recover_hash', $token['token'])->first();

        if (!$dealer) {
            return $response->withRedirect($this->c->router->pathFor('index'));
        }

        $dealer->password = password_hash($postData['password1'], PASSWORD_DEFAULT, ['cost'=>12]);
        $dealer->recover_hash = "";
        $dealer->save();

        $this->c->flash->addMessage('info', 'Password has been changed successfully.');
        return $response->withRedirect($this->c->router->pathFor('index'));
    }  
}