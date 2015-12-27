%[kind : json]
%[file : get%%(self.obName.lower())%%json.php] 
%[path : controllers/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
*
*/

class Get%%(self.obName)%%Json extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%_model');
		$this->load->library('%%(self.obName)%%Service');
		$this->load->library('session');
		$this->load->database();

	}

%%codeForUploadFile = ""
useUpload = False
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		useUpload = True
		attributeCode += """
	/**
	 * Revoie les donnees de fichier encode en base 64
	 * @param int $poiidpoi
	 */
	public function get_file_%(dbName)s($%(keyField)s){
		$model = $this->%(obName_lower)sservice->getUnique($this->db, $%(keyField)s);
		if($model == null){
			return "";
		}
		$objectData = Array();
		$objectData["%(keyField)s"] = $model->%(keyField)s;
		$json_data = "";
	
		if($model->%(dbName)s != null){
			$path = realpath('www/uploads/') .'/'. $model->%(dbName)s;
			$file_data = file_get_contents($path, 'r');
			$b64_encoded = base64_encode($file_data);
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$json_data = 'data:' . $type . ';base64,' . base64_encode($file_data);
		}
		$objectData["%(dbName)s"] = Array( "id" => $model->%(dbName)s , "data" => $json_data );
	
		$data['%(obName_lower)s'] = $objectData;
		$this->load->view('%(obName_lower)s/jsonifyUnique_view', $data);
	}
	""" % { 'dbName' : field.dbName,
			'obName' : self.obName,
			'obName_lower' : self.obName.lower(),
			'keyField' : self.keyFields[0].dbName
		}
		codeForUploadFile += attributeCode
RETURN = codeForUploadFile
%%

}

?>