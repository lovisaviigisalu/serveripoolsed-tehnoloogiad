<?php

namespace App\Controller;

use Slim\Psr7\Response as Response; 
use Psr\Http\Message\ServerRequestInterface as Request;

class ExceptionController extends Controller {
    public function notFound(Request $request) {
        $response = new Response();
        return $this->render($response, 'notfound.html');
    }
};
