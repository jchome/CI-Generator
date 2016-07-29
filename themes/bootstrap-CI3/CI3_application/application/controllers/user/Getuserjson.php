<?php
/*
 * Created by generator
*
*/

class GetUserJson extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('UserService');
		$this->load->library('session');
		$this->load->database();

	}


	/**
	 * Revoie les donnees de fichier encode en base 64
	 * @param int $poiidpoi
	 */
	public function get_file_usrfipho($usridusr){
		$model = $this->userservice->getUnique($this->db, $usridusr);
		if($model == null){
			return "";
		}
		$objectData = Array();
		$objectData["usridusr"] = $model->usridusr;
		$json_data = "";
	
		if($model->usrfipho != null){
			$path = realpath('www/uploads/') .'/'. $model->usrfipho;
			$file_data = file_get_contents($path, 'r');
			$b64_encoded = base64_encode($file_data);
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$json_data = 'data:' . $type . ';base64,' . base64_encode($file_data);
		}
		$objectData["usrfipho"] = Array( "id" => $model->usrfipho , "data" => $json_data );
	
		$data['user'] = $objectData;
		$this->load->view('user/jsonifyUnique_view', $data);
	}
	

}

?>