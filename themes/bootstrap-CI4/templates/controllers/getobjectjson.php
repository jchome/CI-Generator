%[kind : json]
%[file : Get%%(self.obName.lower())%%json.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

class Get%%(self.obName)%%Json extends \App\Controllers\Generated\AjaxController {
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
			return $this->statusError('NOT FOUND');
		} else{
			return $this->statusOK($result);
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
			return $this->statusError('NOT FOUND');
		}else{
			$model->delete($%%(self.keyFields[0].dbName)%%);
			return $this->statusOK('done');
		}

		
	}

}

?>
