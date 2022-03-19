%[kind : json]
%[file : List%%(self.obName.lower())%%sjson.php] 
%[path : Controllers/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

class List%%(self.obName)%%sJson extends \App\Controllers\BaseController {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%Model');
		$this->load->library('%%(self.obName)%%Service');
		$this->load->library('session');
		$this->load->database();
	}

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index(){
		// recuperation des donnees
		$data['data'] = $this->%%(self.obName.lower())%%service->getAll($this->db);
		$this->load->view('json/jsonifyData_view', $data);
	}

%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	else:
		attributeCode += """
	public function findBy_%(fieldDbName)s($%(fieldDbName)s, $orderBy = null, $limit = 50, $offset = 0){
		$asc = null;
		$data['data'] = $this->%(objectNameLower)sservice->getAllBy_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s), $orderBy, $asc, $limit, $offset);
		$this->load->view('json/jsonifyData_view', $data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower(),
			'obName' : self.obName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	else:
		attributeCode += """
	public function countBy_%(fieldDbName)s($%(fieldDbName)s){
		$data['data'] = $this->%(objectNameLower)sservice->countBy_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('json/jsonifyData_view', $data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower()
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%


%%allAttributeCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	else:
		attributeCode += """
	public function countBy_%(fieldDbName)sGET(){
		$%(fieldDbName)s = $this->input->get('%(fieldDbName)s');
		$data['data'] = $this->%(objectNameLower)sservice->countBy_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('json/jsonifyData_view', $data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower()
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
		$data['data'] = $this->%(objectNameLower)sservice->getAllLike_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('json/jsonifyData_view', $data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower()
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

}
?>
