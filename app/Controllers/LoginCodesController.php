<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\LoginCodes;

class LoginCodesController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $login_codes = LoginCodes::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'login-codes.twig', [
            'title' => $this->c->lang->label()['login_codes_label'],
            'data' => $login_codes,
        ]);
    }

    public function postLoginCodes (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        dump($postData);
    }
}