<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\MarginOfProfit;
use Illuminate\Database\Capsule\Manager as DB;

class ProfitMarginController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $data = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))->groupBy('sub_group', 'group_name')->get()->groupBy('group_name')->toArray();
        
        $margin_profit = MarginOfProfit::where('customer_number', $_SESSION['customer_number'])->first();
        return $this->c->view->render($response, 'profit-margin.twig', [
            'title' => $this->c->lang->label()['profit_margin_label'],
            'data'  => $data,
            'margin_profit' => $margin_profit,
        ]);
    }
}