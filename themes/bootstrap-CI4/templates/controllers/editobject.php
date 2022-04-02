%[kind : controllers]
%[file : Edit%%(self.obName.lower())%%.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\%%(self.obName.title())%%;

class Edit%%(self.obName)%% extends \App\Controllers\BaseController {

	/**
	 * Affichage des infos
	 */
	public function index($%%(self.keyFields[0].dbName)%%){

		if(session()->get('user_name') == "") {
			return redirect()->to('welcome/index');
		}
		
		helper('form');
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$model = $this->%%(self.obName.lower())%%Model->find($%%(self.keyFields[0].dbName)%%);

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
		$this->view('%%(self.obName.title())%%/edit%%(self.obName.lower())%%', $data);
	}

	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		helper(['form', 'url']);

		$validation =  \Config\Services::validation();
		/*
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
		*/

		if (! $this->validate([
%%allAttributeCode = ""
for field in self.fields:
	rule = "trim"
	if not field.nullable:
		rule += "|required"
	
	if field.autoincrement:
		continue

	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode = """
		'%(dbName)s_file' => '%(rule)s',""" % {
			'dbName': field.dbName,
			'objectObName': self.obName.title(),
			'rule': rule
		}
	else:	
		attributeCode = """
		'%(dbName)s' => '%(rule)s',""" % {
			'dbName': field.dbName,
			'objectObName': self.obName.title(),
			'rule': rule
		}
	if attributeCode != "":
		allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
		])) {
			$this->view('%%(self.obName.title())%%/edit%%(self.obName.lower())%%');
		}

		// Mise a jour des donnees en base
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$key = $this->request->getPost('%%(self.keyFields[0].dbName)%%');
		$oldModel = $this->%%(self.obName.lower())%%Model->find($key);

		$data = [
%%
includesKey = True;
RETURN = self.dbAndObVariablesList("\t'(dbVar)s' => $this->request->getPost('(dbVar)s'),", 'dbVar', 'obVar', 2, includesKey)
%%
		];

		$this->%%(self.obName.lower())%%Model->update($key, $data);
		
%%codeForUploadFile = ""
useUpload = False
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		useUpload = True
		attributeCode += """
		
		log_message('debug','[Edit%(obName_lower)s.php] : DEMARRAGE de l\\\'upload');
		// Suppression de l'ancien fichier %(dbName)s : %(desc)s
		if( $oldModel['%(dbName)s'] != "" && $data['%(dbName)s'] == ""){
			unlink(PUBLIC_PATH . '/uploads/' . $oldModel['%(dbName)s']);
		}
		// Upload du nouveau fichier %(dbName)s : %(desc)s
		$%(dbName)s_file = $this->request->getFile('%(dbName)s_file');
		if($%(dbName)s_file != "") {
			$%(dbName)s_ext = $%(dbName)s_file->guessExtension();

			if (! $%(dbName)s_file->hasMoved()) {
				$filepath = WRITEPATH . 'uploads/' . $%(dbName)s_file->store();
				// Rename file to match with this object
				$data['%(dbName)s'] = '%(obName_lower)s_' . $data['%(keyField)s'] . '_%(dbName)s.' . $%(dbName)s_ext;
				rename($filepath, PUBLIC_PATH . '/uploads/' . $data['%(dbName)s']);

				// Remove uploaded file temp name
				if( file_exists($filepath) ){
					unlink($filepath);
				}
				// Autre fichier à télécharger (autre extension)
				if($oldModel['%(dbName)s'] != $data['%(dbName)s']){
					unlink(PUBLIC_PATH . '/uploads/' . $oldModel['%(dbName)s']);
				}
			} else {
				session()->setFlashData('msg_error', lang('App.message.upload-failed'));
				$this->view('%(obName_title)s/edit%(obName_lower)s');
			}
		}""" % {'dbName' : field.dbName, 
			'desc' : field.description, 
			'obName_title' : self.obName.title(),
			'obName_lower' : self.obName.lower(),
			'keyField' : self.keyFields[0].dbName
		}
	codeForUploadFile += attributeCode

if useUpload:
	codeForUploadFile = codeForUploadFile + """
		$this->%(obName_lower)sModel->update($key, $data);
""" % { 'obName_lower' : self.obName.lower() }
RETURN = codeForUploadFile
%%

		session()->setFlashData('msg_confirm', lang('%%(self.obName.title())%%.message.confirm.modified'));
		return redirect()->to('%%(self.obName.title())%%/list%%(self.obName.lower())%%s/index');
	}


	public function view($page, $data = [])
	{
		if (! is_file(APPPATH . 'Views/' . $page . '.php')) {
			print("Cannot open view to ". $page);
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		echo view('templates/header', ["menu" => "%%(self.obName.title())%%"]);
		echo view($page, $data);
		echo view('templates/footer');
	}

}
?>
