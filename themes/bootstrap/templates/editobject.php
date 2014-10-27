%[kind : controllers]
%[file : edit%%(self.obName.lower())%%.php] 
%[path : controllers/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

class Edit%%(self.obName)%% extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%_model');
		$this->load->library('%%(self.obName)%%Service');
		$this->load->library('session');
		$this->load->helper('template');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->database();
%%allAttributeCode = ""
# inclure les modeles des objets référencés

for field in self.fields:
	attributeCode = ""
	if field.referencedObject:
		attributeCode += """
		$this->load->model('%(referencedObject)s_model');
		$this->load->library('%(referencedObject)sService');""" % {'referencedObject': field.referencedObject.obName}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
		
	}


	/**
	 * Affichage des infos
	 */
	public function index($%%(self.keyFields[0].dbName)%%){
		$model = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.keyFields[0].dbName)%%);
		$data['%%(self.obName.lower())%%'] = $model;
%%allAttributeCode = ""
# inclure les objets référencés dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sservice->getAll($this->db);""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower()
		}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

		$this->load->view('%%(self.obName.lower())%%/edit%%(self.obName.lower())%%_view',$data);
	}

	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
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
			$this->load->view('%%(self.obName.lower())%%/edit%%(self.obName.lower())%%_view');
		}
		
		// Mise a jour des donnees en base
		$model = new %%(self.obName)%%_model();
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
			$uploadDataFile_%(dbName)s = $this->upload->data('%(dbName)s_file');
			$codeErrors = $this->upload->display_errors() . "ext: [" . $uploadDataFile_%(dbName)s['file_ext'] ."] type mime: [" . $uploadDataFile_%(dbName)s['file_type'] . "]";
			if($this->upload->display_errors() == '<p>'.$this->lang->line('upload_no_file_selected').'</p>'
				|| $this->upload->display_errors() == '<p>upload_no_file_selected</p>'){ // if not translated
				$codeErrors = "NO_FILE";
			}
		}else{
			$uploadDataFile_%(dbName)s = $this->upload->data('%(dbName)s_file');
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
		$this->session->set_flashdata('msg_confirm', $this->lang->line('%%(self.obName.lower())%%.message.confirm.modified'));

		redirect('%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index');
	}

}
?>
