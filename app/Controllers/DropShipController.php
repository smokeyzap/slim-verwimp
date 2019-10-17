<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;

class DropShipController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $item_list = Article::orderBy('id', 'asc')->first();
    	$image = Image::where('line_number', $item_list->sort_number)->first();
        
        return $this->c->view->render($response, 'dropship.twig', [
            'title' => $this->c->lang->label()['dropship'],
            'item_list' => $item_list,
            'image' => $image,
        ]);
    }
}