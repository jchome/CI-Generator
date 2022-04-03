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
		
		helper(['form', 'database']);
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$model = $this->%%(self.obName.lower())%%Model->find($%%(self.keyFields[0].dbName)%%);
%%attributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		$model['%(dbName)s'] = toUiDate($model['%(dbName)s']);""" % {'dbName' : field.dbName }

RETURN = attributeCode
%%
		$data['%%(self.obName.lower())%%'] = $model;
%%allAttributeCode = ""
# inclure les objets references dans l'objet $data

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "default":
		attributeCode += """
		$this->%(referencedObjectLower)sModel = new \App\Models\%(referencedObjectTitle)sModel();
		$data['%(referencedObjectLower)sCollection'] = $this->%(referencedObjectLower)sModel->orderBy('%(fieldDisplay)s', 'asc')->findAll();""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'referencedObjectTitle' : field.referencedObject.obName.title(),
			'fieldDisplay': field.display
		}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
		return $this->view('%%(self.obName.title())%%/edit%%(self.obName.lower())%%', $data);
	}

	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		helper(['form', 'database']);

		$validation =  \Config\Services::validation();
		
		if (! $this->validate([
%%allAttributeCode = ""
for field in self.fields:
	rule = "trim"
	if not field.nullable:
		rule += "|required"
	
	if field.autoincrement:
		continue

	if field.sqlType.upper()[0:4] == "FILE":
		pass
		## no rule for a file
		#attributeCode = """
		#'%(dbName)s_file' => '%(rule)s',""" % {
		#	'dbName': field.dbName,
		#	'objectObName': self.obName.title(),
		#	'rule': rule
		#}
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
			log_message('debug','[Edit%(obName_lower)s.php] : Error in the form !');
			session()->setFlashData('error', $validation->listErrors());
			return redirect()->to('%%(self.obName.title())%%/edit%%(self.obName.lower())%%/index/' 
				. $this->request->getPost('%%(self.keyFields[0].dbName)%%'));
		}

		// Mise a jour des donnees en base
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$key = $this->request->getPost('%%(self.keyFields[0].dbName)%%');
		$oldModel = $this->%%(self.obName.lower())%%Model->find($key);

		$data = [
%%attributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
			'%(dbName)s' => toSqlDate($this->request->getPost('%(dbName)s')),""" % {'dbName' : field.dbName }
	else:
		attributeCode += """
			'%(dbName)s' => $this->request->getPost('%(dbName)s'),""" % {'dbName' : field.dbName }

RETURN = attributeCode
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
				// Autre fichier a telecharger (autre extension)
				if($oldModel['%(dbName)s'] != $data['%(dbName)s'] 
					&& $oldModel['%(dbName)s'] != ""
					&& file_exists(PUBLIC_PATH . '/uploads/' . $oldModel['%(dbName)s'])
					){
					unlink(PUBLIC_PATH . '/uploads/' . $oldModel['%(dbName)s']);
				}
			} else {
				session()->setFlashData('msg_error', lang('App.message.upload-failed'));
				return redirect()->to('%(obName_title)s/edit%(obName_lower)s/index/' 
					. $this->request->getPost('%(keyField)s)'));
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
