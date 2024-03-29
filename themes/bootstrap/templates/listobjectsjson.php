%[kind : json]
%[file : list%%(self.obName.lower())%%sjson.php] 
%[path : controllers/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

class List%%(self.obName)%%sJson extends CI_Controller {

	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('%%(self.obName)%%_model');
		$this->load->library('%%(self.obName)%%Service');
		$this->load->library('session');
		$this->load->database();
%%allAttributeCode = ""
# inclure les modeles des objets référencés

for field in self.fields:
	attributeCode = ""
	if field.referencedObject:
		attributeCode += """
		$this->load->model('%(referencedObject)s_model');
		$this->load->library('%(referencedObject)sService');""" % {'referencedObject': field.referencedObject.obName}
	allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
	}

	/**
	 * Affichage des %%(self.obName)%%s
	 */
	public function index(){
		// recuperation des donnees
		$data['%%(self.obName.lower())%%s'] = $this->%%(self.obName.lower())%%service->getAll($this->db);
		
		$this->load->view('%%(self.obName.lower())%%/jsonifyList_view', $data);
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
		$data['%(objectNameLower)ss'] = $this->%(objectNameLower)sservice->getAllBy_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s), $orderBy, $asc, $limit, $offset);
		$this->load->view('%(objectNameLower)s/jsonifyList_view', $data);
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
		$data['count'] = $this->%(objectNameLower)sservice->countBy_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('%(objectNameLower)s/jsonifyCount_view', $data);
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
		$data['%(objectNameLower)ss'] = $this->%(objectNameLower)sservice->getAllLike_%(fieldDbName)s($this->db, urldecode($%(fieldDbName)s));
		$this->load->view('%(objectNameLower)s/jsonifyList_view', $data);
	}""" % { 'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower()
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

}
?>
