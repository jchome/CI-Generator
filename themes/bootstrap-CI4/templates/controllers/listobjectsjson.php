%[kind : json]
%[file : List%%(self.obName.lower())%%sjson.php] 
%[path : Controllers/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\Generated\%%(self.obName.title())%%;

use CodeIgniter\API\ResponseTrait;

class List%%(self.obName)%%sJson extends \App\Controllers\AjaxController {

	use ResponseTrait;

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index($orderBy = '%%(self.nonKeyFields[0].dbName)%%', $asc = 'asc', $offset = 0){
		helper(['database']);

		// preparer le tri
		$data['orderBy'] = $orderBy;
		$data['asc'] = $asc;
		$limit = 10;
		$pager = \Config\Services::pager();
		// recuperation des donnees
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();

		$data['%%(self.obName.lower())%%s'] = $%%(self.obName.lower())%%Model
			->orderBy($orderBy, $asc)->paginate($limit, 'bootstrap', null, $offset);
		$data['pager'] = $%%(self.obName.lower())%%Model->pager;

		return $this->statusOK($data);

	}

	/**
	 * Get all objects having the key in the list $listOfKeys (string separated by ',')
	 * 
	 */
	public function getAll_%%(self.keyFields[0].dbName)%%($listOfKeys, $orderBy = null){
		$%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();
		$keysArray = explode(',', $listOfKeys);
		if($orderBy == null){
			$orderBy = '%%(self.keyFields[0].dbName)%%';
		}
		$result = $%%(self.obName.lower())%%Model->orderBy($orderBy, 'asc')->find($keysArray);
		return $this->statusOK($result);
	}

%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	else:
		attributeCode += """
	public function findBy_%(fieldDbName)s($%(fieldDbName)s, $orderBy = null, $limit = 50, $offset = 0){
		// recuperation des donnees
		$%(objectNameLower)sModel = new \App\Models\%(objectNameTitle)sModel();
		$result = $%(objectNameLower)sModel->where('%(fieldDbName)s', $%(fieldDbName)s)->findAll($limit, $offset);
		return $this->statusOK($result);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower(),
			'objectNameTitle' : self.obName.title(),
			'obName' : self.obName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%



%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:7] == "VARCHAR" or field.sqlType.upper()[0:4] == "TEXT" :
		attributeCode += """
	public function findLike_%(fieldDbName)s($%(fieldDbName)s){
		$db      = \Config\Database::connect();
		$builder = $db->table('%(tableName)s');
		$builder->like('%(fieldDbName)s', urldecode($%(fieldDbName)s));

		$data['%(objectNameLower)sCollection'] = $builder->get()->getResultArray();
		return $this->statusOK($data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameTitle' : self.obName.title(),
			'objectNameLower' : self.obName.lower(),
			'tableName' : self.dbTableName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

}
?>
