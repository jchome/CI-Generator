<?php 

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;

/**
 * Renders a HTML content
 */
class AjaxController extends \App\Controllers\BaseController {

	// This will provide this return "$this->respond(...);"
	use ResponseTrait;

	protected function statusOK($data = 'Done.') : Response {
		return $this->respond([
			'status' => 'ok',
			'data' => $data
		], 200);
	}

	protected function statusError($cause) : Response {
		return $this->respond([
			'status' => 'error',
			'data' => $cause
		], 200);
	}

	protected function statusFailure($cause) : Response {
		return $this->respond([
			'status' => 'failure',
			'data' => $cause
		], 500);
	}

	protected function statusNotFound($cause) : Response {
		return $this->respond([
			'status' => 'notFound',
			'data' => $cause
		], 404);
	}
}