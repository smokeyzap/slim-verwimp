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

        if (isset($postData['full_access'])) {
            LoginCodes::create([
                'customer_number' => $_SESSION['customer_number'], 
                'password' => $postData['password'], 
                'name' => $postData['name'], 
                'full_access' => 1, 
                'purchase_price_access' => 1, 
                'invoice_access' => 1, 
                'customer_info_access' => 1, 
                'question_access' => 1, 
                'offer_access' => 1, 
                'offer_order' => 1, 
                'offer_favorite' => 1, 
                'new_access' => 1, 
                'new_order' => 1, 
                'new_favorite' => 1, 
                'closeout_access' => 1, 
                'closeout_order' => 1, 
                'closeout_favorite' => 1, 
                'list_access' => 1, 
                'list_order' => 1, 
                'list_favorite' => 1, 
                'index_access' => 1, 
                'index_order' => 1, 
                'index_favorite' => 1, 
                'favorite_access' => 1, 
                'favorite_order' => 1, 
                'favorite_remove' => 1, 
                'order_access' => 1, 
                'order_send' => 1, 
                'order_addition' => 1
            ]);
        } else {
            LoginCodes::create([
                'customer_number' => $_SESSION['customer_number'], 
                'password' => $postData['password'], 
                'name' => $postData['name'], 
                'full_access' => 0, 
                'purchase_price_access' => isset($postData['general_info_purchase'])?1:0, 
                'invoice_access' => isset($postData['general_info_invoices'])?1:0, 
                'customer_info_access' => isset($postData['general_info_customer_info'])?1:0, 
                'question_access' => isset($postData['general_info_question'])?1:0, 
                'offer_access' => isset($postData['offer_access'])?1:0, 
                'offer_order' => isset($postData['offer_order_total'])?1:0, 
                'offer_favorite' => isset($postData['offer_favorites'])?1:0, 
                'new_access' => isset($postData['new_access'])?1:0, 
                'new_order' => isset($postData['new_order_total'])?1:0, 
                'new_favorite' => isset($postData['new_favorites'])?1:0, 
                'closeout_access' => isset($postData['you_snooze_access'])?1:0, 
                'closeout_order' => isset($postData['you_snooze_order_total'])?1:0, 
                'closeout_favorite' => isset($postData['you_snooze_favorites'])?1:0, 
                'list_access' => isset($postData['item_list_access'])?1:0, 
                'list_order' => isset($postData['item_list_order_total'])?1:0, 
                'list_favorite' => isset($postData['item_list_favorites'])?1:0, 
                'index_access' => isset($postData['item_index_access'])?1:0, 
                'index_order' => isset($postData['item_index_order_total'])?1:0, 
                'index_favorite' => isset($postData['item_index_favorites'])?1:0, 
                'favorite_access' => isset($postData['favorites_access'])?1:0, 
                'favorite_order' => isset($postData['favorites_order_total'])?1:0, 
                'favorite_remove' => isset($postData['favorites_delete'])?1:0, 
                'order_access' => isset($postData['order_list_access'])?1:0, 
                'order_send' => isset($postData['order_list_send'])?1:0, 
                'order_addition' => isset($postData['order_list_add'])?1:0
            ]);
        }

        $this->c->flash->addMessage('info', 'New login code has been added successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.login.codes'));
    }

    public function deleteLoginCode (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        LoginCodes::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->delete();
        $this->c->flash->addMessage('info', 'Deleted successfully.');
        return $response->withRedirect($this->c->router->pathFor('get.login.codes'));
    }

    public function editLoginCode (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        $login_code = LoginCodes::where('id', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();

        if (!$login_code) {
            die('Access denied');
        }
        dump($login_code);
        return $this->c->view->render($response, 'edit-login-code.twig', [
            'title' => $this->c->lang->label()['edit'] . ' ' . $this->c->lang->label()['login_codes_label'],
            'data' => $login_code
        ]);
    }

    public function updateLoginCode (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $lc = LoginCodes::where('id', $postData['id'])
                ->where('customer_number', $_SESSION['customer_number'])
                ->first();
        
        if (isset($postData['full_access'])) {
            $lc->password = $postData['password'];
            $lc->name = $postData['name'];
            $lc->full_access = 1;
            $lc->purchase_price_access = 1; 
            $lc->invoice_access = 1;
            $lc->customer_info_access = 1;
            $lc->question_access = 1;
            $lc->offer_access = 1;
            $lc->offer_order = 1;
            $lc->offer_favorite = 1; 
            $lc->new_access = 1;
            $lc->new_order = 1;
            $lc->new_favorite = 1; 
            $lc->closeout_access = 1; 
            $lc->closeout_order = 1;
            $lc->closeout_favorite = 1; 
            $lc->list_access = 1;
            $lc->list_order = 1;
            $lc->list_favorite = 1; 
            $lc->index_access = 1;
            $lc->index_order = 1;
            $lc->index_favorite = 1; 
            $lc->favorite_access = 1; 
            $lc->favorite_order = 1;
            $lc->favorite_remove = 1; 
            $lc->order_access = 1;
            $lc->order_send = 1;
            $lc->order_addition = 1;
            $lc->save();
        } else {
            $lc->password = $postData['password'];
            $lc->name = $postData['name'];
            $lc->full_access = 0;
            $lc->purchase_price_access = isset($postData['general_info_purchase'])?1:0;
            $lc->invoice_access = isset($postData['general_info_invoices'])?1:0;
            $lc->customer_info_access = isset($postData['general_info_customer_info'])?1:0; 
            $lc->question_access = isset($postData['general_info_question'])?1:0;
            $lc->offer_access = isset($postData['offer_access'])?1:0;
            $lc->offer_order = isset($postData['offer_order_total'])?1:0; 
            $lc->offer_favorite = isset($postData['offer_favorites'])?1:0; 
            $lc->new_access = isset($postData['new_access'])?1:0;
            $lc->new_order = isset($postData['new_order_total'])?1:0; 
            $lc->new_favorite = isset($postData['new_favorites'])?1:0; 
            $lc->closeout_access = isset($postData['you_snooze_access'])?1:0; 
            $lc->closeout_order = isset($postData['you_snooze_order_total'])?1:0; 
            $lc->closeout_favorite = isset($postData['you_snooze_favorites'])?1:0; 
            $lc->list_access = isset($postData['item_list_access'])?1:0;
            $lc->list_order = isset($postData['item_list_order_total'])?1:0; 
            $lc->list_favorite = isset($postData['item_list_favorites'])?1:0; 
            $lc->index_access = isset($postData['item_index_access'])?1:0;
            $lc->index_order = isset($postData['item_index_order_total'])?1:0; 
            $lc->index_favorite = isset($postData['item_index_favorites'])?1:0; 
            $lc->favorite_access = isset($postData['favorites_access'])?1:0;
            $lc->favorite_order = isset($postData['favorites_order_total'])?1:0; 
            $lc->favorite_remove = isset($postData['favorites_delete'])?1:0;
            $lc->order_access = isset($postData['order_list_access'])?1:0;
            $lc->order_send = isset($postData['order_list_send'])?1:0;
            $lc->order_addition = isset($postData['order_list_add'])?1:0;
            $lc->save();
        }
        $this->c->flash->addMessage('info', 'Login code has been updated');
        return $response->withRedirect($this->c->router->pathFor('get.login.codes'));
    }
}    