%[kind : json]
%[file : Create%%(self.obName.lower())%%json.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php

/*
 * Created by generator
 * 
 */
namespace App\Controllers\%%(self.obName.title())%%;

class Create%%(self.obName)%%Json extends \App\Controllers\BaseController {
	
	/**
	 * page de creation d'un %%(self.obName.lower())%%
	 */	
	public function index(){
		$data = Array();
%%allAttributeCode = "		// Recuperation des objets references"
# inclure les objets référencés dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sservice->getAll($this->db,'%(fieldDisplay)s');""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'fieldDisplay': field.display
		}
	elif field.sqlType.upper()[0:4] == "ENUM":
		enumTypes = field.sqlType[5:-1]
		attributeCode = """
		$data["enum_%(dbName)s"] = array( """ % {'dbName' : field.dbName}
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """"%(value)s" => "%(text)s",""" % {'value': valueAndText[0].strip(), 'text': valueAndText[1].strip()}
		attributeCode = attributeCode[:-1] + ");"
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

		$this->load->view('%%(self.obName.lower())%%/create%%(self.obName.lower())%%_fancyview', $data);
	}
	
	/**
	 * Ajout d'un %%(self.obName)%%
	 */
	public function add(){
	
		// Insertion en base
		$model = new %%(self.obName)%%Model();
		%%
includesKey = True;
RETURN = self.dbAndObVariablesList("$model->(dbVar)s = $this->input->post('(dbVar)s'); ", 'dbVar', 'obVar', 2, includesKey)
%%
		$this->%%(self.obName.lower())%%service->insertNew($this->db, $model);
%%codeForUploadFile = ""
useUpload = False
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		useUpload = True
		attributeCode += """
		
		$this->upload->initialize($config); // RAZ des erreurs
		// Upload du fichier %(dbName)s : %(desc)s
		$codeErrors = null;
		if ( ! $this->upload->do_upload('%(dbName)s_file')) {
			$uploadDataFile_%(dbName)s = $this->upload->data();
			$codeErrors = $this->upload->display_errors() . "ext: [" . $uploadDataFile_%(dbName)s['file_ext'] ."] type mime: [" . $uploadDataFile_%(dbName)s['file_type'] . "]";
			if($this->upload->display_errors() == $this->lang->line('upload_no_file_selected')
				|| $this->upload->display_errors() == '<p>upload_no_file_selected</p>'){ // if not translated
				$codeErrors = "NO_FILE";
			}
		}else{
			$uploadDataFile_%(dbName)s = $this->upload->data();
		}
	
		if($codeErrors != null && $codeErrors != "NO_FILE") {
			$this->session->set_flashdata('msg_error', $codeErrors);
			$this->%(obName_lower)sservice->delete($this->db, $model);
		} else {
			$model->%(dbName)s = "";
			if($uploadDataFile_%(dbName)s != null && $uploadDataFile_%(dbName)s != "") {
				$model->%(dbName)s = '%(obName)s_%(dbName)s_' . $model->%(keyField)s . '_file' . $uploadDataFile_%(dbName)s['file_ext'];
				rename($path . $uploadDataFile_%(dbName)s, $path . $model->%(dbName)s);
				// suppression du fichier temporaire telecharge
				if( file_exists( $path . $uploadDataFile_%(dbName)s ) ){
					unlink($path . $uploadDataFile_%(dbName)s);
				}
			}
			$this->%(obName_lower)sservice->update($this->db, $model);
		
				
		}""" % { 'dbName' : field.dbName,
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
%%
RETURN = """		$data['%(obName_lower)s'] = $model;""" % { 'obName_lower' : self.obName.lower() }
%%
		$this->load->view('json/jsonifyData_view', $data);
	}
}
?>
