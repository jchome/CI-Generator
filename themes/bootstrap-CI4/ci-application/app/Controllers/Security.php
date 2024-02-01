<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Security extends \App\Controllers\BaseController
{
    use ResponseTrait;

    public function index(){
        $data = [];
        return $this->respond([
			'text' => 'ok',
			'data' => $data
		]);
    }

    public function validateToken($token, $userId){
        helper(['security']);
        $result = check_token($token, $userId, "" /*$_SERVER['REMOTE_ADDR']*/ );
        $output = "FAILED";
        if($result){
            $output = "SUCCESS";
        }
        return $this->respond([
            'text' => 'ok',
            'data' => $output
        ]);
    }

}
