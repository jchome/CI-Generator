%[kind : controllers]
%[file : Import%%(self.obName.lower())%%s.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\%%(self.obName.title())%%;

class Import%%(self.obName)%%s extends \App\Controllers\BaseController {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%Model');
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->database();
	}
	
	public function index(){
		$data = array();
		$this->load->view('%%(self.obName.lower())%%/import%%(self.obName.lower())%%s_view', $data);
	}
	
	public function loadFile(){
		// upload du fichier CSV
		// Chemin de stockage des fichiers : doit etre WRITABLE pour tous
		$config['upload_path'] = realpath('www/uploads/');
		// Voir la configuration des types mimes s'il y a un probleme avec l'extension
		$config['allowed_types'] = 'csv|txt';
		$config['max_size']	= '2000';
		$this->load->library('upload', $config);
		$path = $config['upload_path'] . "/";
	
		$codeErrors = null;
		if ( ! $this->upload->do_upload('import_file')) {
			$uploadDataFile = $this->upload->data('import_file');
			$codeErrors = $this->upload->display_errors() . "ext: [" . $uploadDataFile['file_ext'] ."] type mime: [" . $uploadDataFile['file_type'] . "]";
			if($this->upload->display_errors() == $this->lang->line('upload_no_file_selected')){
				$codeErrors = "NO_FILE";
			}
		}else{
			$uploadDataFile = $this->upload->data('import_file');
		}
	
		if($codeErrors != null && $codeErrors != "NO_FILE") {
			$this->session->set_flashdata('msg_error', $codeErrors);
		} else {
			if($uploadDataFile['file_name'] != null && $uploadDataFile['file_name'] != "") {
				$filename = $path . $uploadDataFile['file_name'];
			}
		}
	
		$data = array();
		if( isset($filename) ){
			$dataProcessed = $this->parseFile($filename, ';');
			$data['dataProcessed'] = $dataProcessed;
			unlink($filename);
		}
	
		$this->load->view('%%(self.obName.lower())%%/import%%(self.obName.lower())%%s_view', $data);
	
	}
	/**
	 * Charge le fichier CSV et importe les donnees
	 * @param String $filename
	 * @param String $separator
	 * @return multitype:%%(self.obName)%%Model
	 */
	private function parseFile($filename, $separator){
		// lecture de l'entete
		$maxSize = 0; // no limit
		$ligneEnCours = 0;
		$dataProcessed = array();
		if (($handle = fopen($filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, $maxSize, $separator)) !== FALSE) {
				$nbColonnes = count($data);
	
				// passer la ligne d'enete
				if($ligneEnCours == 0){
					$ligneEnCours++;
					continue;
				}
	
				// Insertion en base
				$model = new %%(self.obName)%%Model();
%%allAttributeCode = ""
rank = 0
for field in self.fields:
	attributeCode = ""
	if not field.isKey:
		attributeCode += """
				$model->%(dbname)s = $data[%(rank)s];""" % {'dbname':field.dbName, 'rank':rank}
		rank += 1
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%	
				$model->save($this->db);
				$dataProcessed[$ligneEnCours] = $model;
				$ligneEnCours++;
			}
			fclose($handle);
		}
		return $dataProcessed;
	}
}
?>