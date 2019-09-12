<?php
namespace App\Middleware;
use App\Models\Dealer;
class NotAuth extends Middleware
{

    public function __invoke($request, $response, $next)
    {   
        $dealer = new Dealer;

        if($dealer->check()) {
            return $response->withRedirect($this->c->router->pathFor('get.item.list'));
        }

        $response = $next($request,$response);
        return $response;
    }
}