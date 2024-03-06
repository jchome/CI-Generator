<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class SecurityFilter implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){
        $referer = "";
        if( array_key_exists('HTTP_REFERER', $request->getServer())){
            $referer = $request->getServer()['HTTP_REFERER'];
        }else{
            $referer = $request->getServer()['HTTP_HOST'];
            if( str_starts_with(base_url(), 'http://') ){
                $referer = 'http://' . $referer;
            }else{
                $referer = 'https://' . $referer;
            }
        }
        log_message('debug', 'SecurityFilter#before - referer=' . $referer );
        log_message('debug', 'SecurityFilter#before - base_url=' . base_url() );
        
        if( $referer == "" || str_starts_with($referer, base_url()) || str_starts_with(base_url(), $referer)){
            return $request;
        }

        //log_message('debug', 'SecurityFilter#before - METHOD=' . $request->getMethod());
        if($request->getMethod() == 'options'){
            $response = Services::response();
            $response->setStatusCode(200);
            $response->setBody(`{"status": "ok", "data": ""}`);
            $response->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Headers', '*') 
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, PUT, DELETE');
            //log_message('debug', 'SecurityFilter#before - RETURNING OK.');
            return $response;
        }

        log_message('debug', 'SecurityFilter#before - getHeaderLine=' . $request->getHeaderLine('X-YouDance'));

        if($request->getHeaderLine('X-YouDance') != "YouDance-App"){
            $response = Services::response();
            $response->setStatusCode(500);
            $response->setBody(`{"status": "failure", "data": "Invalid header"}`);
            $response->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Headers', '*') 
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, PUT, DELETE');
            log_message('debug', 'SecurityFilter#before - Invalid header');

            return $response;
        }
        // Proceed the request in the controller

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        // Nothing to do
        $response->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Headers', '*') 
            ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, POST, PUT, DELETE');
    }

}