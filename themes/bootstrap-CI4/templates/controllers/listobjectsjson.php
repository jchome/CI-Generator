%[kind : json]
%[file : List%%(self.obName.lower())%%sjson.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */
namespace App\Controllers\%%(self.obName.title())%%;

use CodeIgniter\API\ResponseTrait;

class List%%(self.obName)%%sJson extends \App\Controllers\BaseController {

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
		$this->%%(self.obName.lower())%%Model = new \App\Models\%%(self.obName.title())%%Model();

		$data['%%(self.obName.lower())%%s'] = $this->%%(self.obName.lower())%%Model
			->orderBy($orderBy, $asc)->paginate($limit, 'bootstrap', null, $offset);
		$data['pager'] = $this->%%(self.obName.lower())%%Model->pager;

		return $this->respond([
			'text' => 'ok',
			'data' => $data
		]);

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
		$this->%(objectNameLower)sModel = new \App\Models\%(objectNameTitle)sModel();
		$model = $this->%(objectNameLower)sModel->find($%(fieldDbName)s);
		return $this->respond([
			'text' => 'ok',
			'data' => $model
		]);
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
	/*public function findLike_%(fieldDbName)s($%(fieldDbName)s){
		$data['data'] = $this->%(objectNameLower)sservice->getAllLike_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('json/jsonifyData_view', $data);
	}*/""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower()
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

}
?>
