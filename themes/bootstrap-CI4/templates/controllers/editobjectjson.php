%[kind : controllers0]
%[file : Edit%%(self.obName.lower())%%json.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

class Edit%%(self.obName)%%Json extends \App\Controllers\BaseController {

	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%Model');
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
		$this->load->model('%(referencedObject)sModel');
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
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sservice->getAll($this->db,'%(fieldDisplay)s');""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'fieldDisplay': field.display
		}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

		$this->load->view('Generated/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%_fancyview',$data);
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
			$this->load->view('Generated/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%_fancyview');
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
		
		log_message('debug','[Edit%(obName_lower)s.php] : DEMARRAGE de l\\\'upload');
		$this->upload->initialize($config); // RAZ des erreurs
		// Suppression de l'ancien fichier %(dbName)s : %(desc)s
		if( $oldModel->%(dbName)s != "" && $model->%(dbName)s == ""){
			unlink($path . $oldModel->%(dbName)s);
		}
		// Upload du nouveau fichier %(dbName)s : %(desc)s
		$codeErrors = null;
		if ( ! $this->upload->do_upload('%(dbName)s_file')) {
			$codeErrors = $this->upload->display_errors() . "ext: [" . $this->upload->data('file_ext') ."] type mime: [" . $this->upload->data('file_type') . "]";
			if($this->upload->display_errors() == '<p>'.$this->lang->line('upload_no_file_selected').'</p>'
				|| $this->upload->display_errors() == '<p>upload_no_file_selected</p>'){ // if not translated
				$codeErrors = "NO_FILE";
			}
		}else{
			log_message('debug','[Edit%(obName_lower)s.php] : PAS d\\\'erreur sur le nouveau fichier');
			$uploadDataFile_%(dbName)s = $this->upload->data('file_name');
		}
	
		if($codeErrors != null && $codeErrors != "NO_FILE") {
			$this->session->set_flashdata('msg_error', $codeErrors);
		}else if( $codeErrors == "NO_FILE" ){
			// rien a faire
			log_message('debug','[Edit%(obName_lower)s.php] : PAS de fichier');
		}else{
			log_message('debug','[Edit%(obName_lower)s.php] : TRAITEMENT du fichier');
			$model->%(dbName)s = "";
			if($uploadDataFile_%(dbName)s != null && $uploadDataFile_%(dbName)s != "") {
				log_message('debug','[Edit%(obName_lower)s.php] : RENOMMAGE du nouveau fichier');
				$model->%(dbName)s = '%(obName)s_%(dbName)s_' . $model->%(keyField)s . '_file' . $this->upload->data('file_ext');
				rename($path . $uploadDataFile_%(dbName)s, $path . $model->%(dbName)s);
				// suppression du fichier temporaire telecharge
				if( file_exists( $path . $uploadDataFile_%(dbName)s ) ){
					unlink($path . $uploadDataFile_%(dbName)s);
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

		redirect('Generated/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index');
	}

}
?>
