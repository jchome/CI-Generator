%[kind : json]
%[file : Get%%(self.obName.lower())%%json.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

use CodeIgniter\API\ResponseTrait;

class Get%%(self.obName)%%Json extends \App\Controllers\BaseController {
	use ResponseTrait;
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
	
		$data['data'] = $objectData;
		$this->load->view('json/jsonifyData_view', $data);
	}
	""" % { 'dbName' : field.dbName,
			'obName' : self.obName,
			'obName_lower' : self.obName.lower(),
			'keyField' : self.keyFields[0].dbName
		}
		codeForUploadFile += attributeCode
RETURN = codeForUploadFile
%%

	/**
	* Affichage des infos
	*/
	public function get($%%(self.keyFields[0].dbName)%%){
%%
field = self.keyFields[0]
allAttributeCode = """
		$%(objectNameLower)sModel = new \App\Models\%(objectNameTitle)sModel();
		$result = $%(objectNameLower)sModel->find($%(fieldDbName)s);
		if( $result == null ){
			return $this->respond([
				'status' => 'KO',
				'data' => 'NOT FOUND'
			]);
		} else{
			return $this->respond([
				'status' => 'ok',
				'data' => $result
			]);
		}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower(),
			'objectNameTitle' : self.obName.title(),
			'obName' : self.obName
		}
RETURN = allAttributeCode
%%
	}

	/**
	 * Affichage des infos
	 */
	public function edit($%%(self.keyFields[0].dbName)%%){
		/*
		$model = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.keyFields[0].dbName)%%);
		$data['%%(self.obName.lower())%%'] = $model;
%%allAttributeCode = ""
# inclure les objets référencés dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sservice->getAll($this->db,'%(fieldDisplay)s');""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'fieldDisplay': field.display
		}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%		
	
		$this->load->view('%%(self.obName.lower())%%/edit%%(self.obName.lower())%%_fancyview',$data);
		*/
	}
	
	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		/*
		$this->form_validation->set_error_delimiters('', '');
%%allAttributeCode = ""
for field in self.fields:
	rule = "trim"
	if not field.nullable:
		rule += "|required"
		
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode = """
		$this->form_validation->set_rules('%(dbName)s_file', 'lang:%(objectObName)s.form.%(dbName)s.label', '%(rule)s');""" % {
			'dbName': field.dbName,
			'objectObName': self.obName.lower(),
			'rule': rule
		}
	else:
		attributeCode = """
		$this->form_validation->set_rules('%(dbName)s', 'lang:%(objectObName)s.form.%(dbName)s.label', '%(rule)s');""" % {
			'dbName': field.dbName,
			'objectObName': self.obName.lower(),
			'rule': rule
		}
	if attributeCode != "":
		allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
		
		if($this->form_validation->run() == FALSE){
			$data = Array();
			$data['data'] = Array('errors' => validation_errors());
			$this->load->view('json/jsonifyData_view', $data);
			return;
		}
		
		// Mise a jour des donnees en base
		$model = new %%(self.obName)%%Model();
		$oldModel = $this->%%(self.obName.lower())%%service->getUnique($this->db, $this->input->post('%%(self.keyFields[0].dbName)%%') );
		%%
codeForAttributes = ""
for field in self.fields:
	codeForField = """
		$model->%(dbName)s = $this->input->post('%(dbName)s');""" % {'dbName' : field.dbName }
	codeForAttributes += codeForField
RETURN = codeForAttributes
%%
		$this->%%(self.obName.lower())%%service->update($this->db, $model);
		
%%codeForUploadFile = ""
useUpload = False
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		useUpload = True
		attributeCode += """
		
		$this->upload->initialize($config); // RAZ des erreurs
		// Suppression de l'ancien fichier %(dbName)s : %(desc)s
		if( $oldModel->%(dbName)s != "" && $model->%(dbName)s == ""){
			unlink($path . $oldModel->%(dbName)s);
		}
		// Upload du nouveau fichier %(dbName)s : %(desc)s
		$codeErrors = null;
		if ( ! $this->upload->do_upload('%(dbName)s_file')) {
			$uploadDataFile_%(dbName)s = $this->upload->data();
			$codeErrors = $this->upload->display_errors() . "ext: [" . $uploadDataFile_%(dbName)s['file_ext'] ."] type mime: [" . $uploadDataFile_%(dbName)s['file_type'] . "]";
			if($this->upload->display_errors() == '<p>'.$this->lang->line('upload_no_file_selected').'</p>'
				|| $this->upload->display_errors() == '<p>upload_no_file_selected</p>'){ // if not translated
				$codeErrors = "NO_FILE";
			}
		}else{
			$uploadDataFile_%(dbName)s = $this->upload->data();
		}
	
		if($codeErrors != null && $codeErrors != "NO_FILE") {
			$this->session->set_flashdata('msg_error', $codeErrors);
		}else if( $codeErrors == "NO_FILE" ){
			// rien a faire
		}else{
			$model->%(dbName)s = "";
			if($uploadDataFile_%(dbName)s['file_name'] != null && $uploadDataFile_%(dbName)s['file_name'] != "") {
				$model->%(dbName)s = '%(obName)s_%(dbName)s_' . $model->%(keyField)s . '_file' . $uploadDataFile_%(dbName)s['file_ext'];
				rename($path . $uploadDataFile_%(dbName)s['file_name'], $path . $model->%(dbName)s);
				// suppression du fichier temporaire telecharge
				if( file_exists( $path . $uploadDataFile_%(dbName)s['file_name'] ) ){
					unlink($path . $uploadDataFile_%(dbName)s['file_name']);
				}
			}
			$this->%(obName_lower)sservice->update($this->db, $model);
		}""" % {'dbName' : field.dbName, 
			'desc' : field.description, 
			'obName' : self.obName,
			'obName_lower' : self.obName.lower(),
			'keyField' : self.keyFields[0].dbName
		}
	codeForUploadFile += attributeCode

if useUpload:
	codeForUploadFile = """
		// Configuration pour chargement des fichiers 
		// Chemin de stockage des fichiers : doit etre WRITABLE pour tous
		$config['upload_path'] = realpath('www/uploads/');
		// Voir la configuration des types mimes s'il y a un probleme avec l'extension
		$config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|gif|jpg|png|jpeg|zip|rar|ppt|pptx|mp3';
		$config['max_size']	= '2000';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$this->load->library('upload', $config);
		$path = $config['upload_path'] . "/";
""" + codeForUploadFile
		
RETURN = codeForUploadFile
%%
		$data['data'] = $model;
		$this->load->view('json/jsonifyData_view', $data);
		*/
	}

	/**
	 * Suppression d'un %%(self.obName)%%
	 * @param $%%(self.keyFields[0].dbName)%% identifiant a supprimer
	 */
	function delete($%%(self.keyFields[0].dbName)%%){
		$model = new \App\Models\%%(self.obName.title())%%Model();
		$result = $model->find($%%(self.keyFields[0].dbName)%%);

%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """
		$path = realpath('www/uploads/');
		if( $model->%(field_dbName)s && file_exists( $path . $model->%(field_dbName)s ) ){
			unlink($path . $model->%(field_dbName)s);
		}
""" % { 'field_dbName' : field.dbName,
		'keyfield_dbname' : self.keyFields[0].dbName
	}
	if attributeCode != "":
		allAttributeCode += attributeCode

RETURN = allAttributeCode
%%

		if($result == null){
			return $this->respond([
				'status' => 'KO',
				'data' => 'NOT FOUND',
			]);
		}else{
			$model->delete($%%(self.keyFields[0].dbName)%%);
			return $this->respond([
				'status' => 'ok',
			]);
		}

		
	}

}

?>
