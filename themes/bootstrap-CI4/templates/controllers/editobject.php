%[kind : controllers]
%[file : Edit%%(self.obName.lower())%%.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

class Edit%%(self.obName)%% extends \App\Controllers\HtmlController {

	/**
	 * Affichage des infos
	 */
	public function index($%%(self.keyFields[0].dbName)%%){

		if(session()->get('user_id') == "") {
			return redirect()->to('welcome/index');
		}
		
		helper(['form', 'database']);
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$model = $%%(self.obName.lower())%%Model->find($%%(self.keyFields[0].dbName)%%);
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
		$%(referencedObjectLower)sModel = new \App\Models\%(referencedObjectTitle)sModel();
		$data['%(referencedObjectLower)sCollection'] = $%(referencedObjectLower)sModel->orderBy('%(fieldDisplay)s', 'asc')->findAll();""" % {
			'referencedObjectLower' : field.referencedObject.obName.lower(),
			'referencedObjectTitle' : field.referencedObject.obName.title(),
			'fieldDisplay': field.display
		}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
		return $this->view('Generated/%%(self.obName.title())%%/edit%%(self.obName.lower())%%', $data, '%%(self.obName.title())%%');
	}

	/**
	 * Sauvegarde des modifications
	 */
	public function save(){
		helper(['form', 'database', 'security']);

		$validation =  \Config\Services::validation();
		
		if (! $this->validate([
%%allAttributeCode = ""
for field in self.fields:
	rule = "trim"
	if field.sqlType.upper()[0:4] == "FILE" or field.sqlType.upper()[0:4] == "FLAG":
		continue

	if not field.nullable:
		## The Required attribute is not valid for FLAG field
		rule += "|required"
	
	if field.autoincrement:
		continue

	attributeCode = """
			'%(dbName)s' => '%(rule)s',""" % {
		'dbName': field.dbName,
		'objectObName': self.obName.title(),
		'rule': rule
	}
	if attributeCode != "":
		allAttributeCode += attributeCode
RETURN = allAttributeCode.lstrip()
%%
		])) {
			log_message('debug','[Edit%%(self.obName.lower())%%.php] : Error in the form !');
			session()->setFlashData('error', $validation->listErrors());
			return redirect()->to('Generated/%%(self.obName.title())%%/edit%%(self.obName.lower())%%/index/' 
				. $this->request->getPost('%%(self.keyFields[0].dbName)%%'));
		}

		// Mise a jour des donnees en base
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$key = $this->request->getPost('%%(self.keyFields[0].dbName)%%');
		$oldModel = $%%(self.obName.lower())%%Model->find($key);

		$data = [
%%attributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
			'%(dbName)s' => toSqlDate($this->request->getPost('%(dbName)s')),""" % {'dbName' : field.dbName }
	
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """
			'%(dbName)s' => generateHash($this->request->getPost('%(dbName)s')),""" % {'dbName' : field.dbName }
		
	else:
		attributeCode += """
			'%(dbName)s' => $this->request->getPost('%(dbName)s'),""" % {'dbName' : field.dbName }

RETURN = attributeCode
%%
		];
%%attributeCode = ""
for field in self.fields:
	if field.nullable:
		attributeCode += """
		if($data['%(dbName)s'] == ""){
			$data['%(dbName)s'] = null;
		}""" % {'dbName' : field.dbName }
RETURN = attributeCode
%%

		$%%(self.obName.lower())%%Model->update($key, $data);
		
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
		$%(obName_lower)sModel->update($key, $data);
""" % { 'obName_lower' : self.obName.lower() }
RETURN = codeForUploadFile
%%

		session()->setFlashData('msg_confirm', lang('generated.%%(self.obName.title())%%.message.confirm.modified'));
		return redirect()->to('Generated/%%(self.obName.title())%%/list%%(self.obName.lower())%%s/index');
	}


}
?>
