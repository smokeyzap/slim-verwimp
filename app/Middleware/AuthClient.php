<?php
namespace App\Middleware;
use App\Models\Dealer;
class AuthClient extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $dealer = new Dealer;
        //if not signed in redirect to login
        if(!$dealer->check()) {
            $this->c->flash->addMessage('error','You must log in first.');
            return $response->withRedirect($this->c->router->pathFor('index'));
        }

        $response = $next($request,$response);
        return $response;
    }
}