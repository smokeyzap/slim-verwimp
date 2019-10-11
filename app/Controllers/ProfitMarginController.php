<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\MarginOfProfit;

class ProfitMarginController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $margin_profit = MarginOfProfit::where('customer_number', $_SESSION['customer_number'])->first();

        $group_names = Article::distinct()->orderBy('group_name', 'asc')->get(['group_name']);
        
        $sub_group = [];

        $group_sub_group = [];

        $data = [];
        foreach ($group_names as $group_name) {
            foreach(Article::distinct()->where('group_name', $group_name->group_name)->orderBy('sub_group', 'asc')->get(['sub_group']) as $key => $value){
                foreach (Article::distinct()->where('group_name', $group_name->group_name)->where('sub_group', $value->sub_group)->orderBy('sub_group', 'asc')->get(['title']) as $k => $v) {
                    $data[] = [
                        'group_name' => $group_name->group_name,
                        'sgt' => [
                            'sub_group' => [
                                $value->sub_group => $v->title
                            ] 
                        ]
                    ];
                }
            }
        }
        

        dump($data);
        // foreach ($sub_group as $key => $value) {
        //     foreach ($value as $s) {
        //         $data[] = [
        //             'sgt' => [
        //                 'sub_group' => $s->sub_group,
        //                 'title' => Article::distinct()->where('group_name', $key)->where('sub_group', $s->sub_group)->orderBy('sub_group', 'asc')->get(['title']),
        //                 ]
        //         ];
        //     }
        // }
        
        //dump($group_sub_group);
        return $this->c->view->render($response, 'profit-margin.twig', [
            'title' => $this->c->lang->label()['profit_margin_label'],
            'group_names' => $group_names,
            'margin_profit' => $margin_profit,
        ]);
    }
}