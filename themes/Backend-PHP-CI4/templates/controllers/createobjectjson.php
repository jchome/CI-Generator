%[kind : json]
%[file : Create%%(self.obName.lower())%%json.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php

/*
 * Created by generator
 * 
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

class Create%%(self.obName)%%Json extends \App\Controllers\AjaxController {

	/**
	 * page de creation d'un %%(self.obName.lower())%%
	 */	
	public function index(){
		$data = Array();
		/*
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
		enumTypes = field.sqlType[5:-1].replace(';',',')
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
*/
		echo view('%%(self.obName.lower())%%/create%%(self.obName.lower())%%_fancyview', $data);
	}
	
	/**
	 * Ajout / Mise a jour d'un %%(self.obName)%%
	 * Si le champ '%%(self.keyFields[0].dbName)%%' est vide, un nouvel enregistrement sera cree
	 */
	public function save(){
	
		helper(['form', 'database', 'security']);
		$validation =  \Config\Services::validation();

		if (! $this->validate([
%%allAttributeCode = ""
for field in self.fields:
	rule = "trim"
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	
	if field.autoincrement:
		continue

	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		## The Required attribute is not valid for FLAG field
		rule += "|required"

	attributeCode = """			'%(dbName)s' => '%(rule)s',
""" % {
		'dbName': field.dbName,
		'objectObName': self.obName.title(),
		'rule': rule
	}
	if attributeCode != "":
		allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
		])) {
			$data = $this->getData();
			$data['validation'] = $this->validator;
			return $this->respond([
				'status' => 'error',
				'data' => $data
			]);
		}

		// Insertion en base
		$data = [
%%attributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """			'%(dbName)s' => toSqlDate($this->request->getPost('%(dbName)s')),
""" % {'dbName' : field.dbName }
	
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """			'%(dbName)s' => generateHash($this->request->getPost('%(dbName)s')),
""" % {'dbName' : field.dbName }

	else:
		attributeCode += """			'%(dbName)s' => $this->request->getPost('%(dbName)s'),
""" % {'dbName' : field.dbName }

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
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		
		$%%(self.obName.lower())%%Model->save($data);
		if($data['%%(self.keyFields[0].dbName)%%'] == null || $data['%%(self.keyFields[0].dbName)%%'] == ""){
			$data['%%(self.keyFields[0].dbName)%%'] = $%%(self.obName.lower())%%Model->getInsertID();
		}

%%codeForUploadFile = ""
useUpload = False
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		useUpload = True
		attributeCode += """
		
		log_message('debug','[Create%(obName_lower)s.php] : DEMARRAGE de l\\\'upload');
		
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
		$%(obName_lower)sModel->update($data['%(keyField)s'], $data);
""" % { 'obName_lower' : self.obName.lower(),
		'keyField' : self.keyFields[0].dbName
}

RETURN = codeForUploadFile
%%

		return $this->respond([
			'status' => 'ok',
			'data' => $data
		]);
		
	}
	


	/**
	 * Recuperation des objets references
	 */
	private function getData() {
		$data = Array();

%%allAttributeCode = ""
# inclure les objets référencés dans l'objet $data

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
	elif field.sqlType.upper()[0:4] == "ENUM":
		enumTypes = field.sqlType[5:-1].replace(';',',')
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
		return $data;
	}
}