<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\Artprop;
use Illuminate\Database\Capsule\Manager as DB;

class ItemIndexController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $bicycles = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->where('categorie', 'FTS')
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $bike_parts = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->where('categorie', 'FO')
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $scooter_parts = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->whereIn('categorie', ['FBO','BO'])
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $artprops = Artprop::distinct()->get(['template'])->where('template', '!=', "");
        
        
        return $this->c->view->render($response, 'item_index.twig', [
            'title' => $this->c->lang->label()['item_index'],
            'bicycles' => $bicycles,
            'bike_parts' => $bike_parts,
            'scooter_parts' => $scooter_parts,
            'artprops' => $artprops,
        ]);
    }

    public function getArticles (Request $request, Response $response) 
    {
        $postData = $request->getAttribute('data');
        echo $postData;
    }
}