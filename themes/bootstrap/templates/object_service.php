%[kind : helpers]
%[file : %%(self.obName)%%Service.php] 
%[path : libraries]
<?php

/*
 * Created by generator
 *
 */

require_once(APPPATH.'libraries/DAOService.php');


class %%(self.obName)%%Service extends DAOService{

	public function __construct($params = array()){
		parent::__construct("%%(self.dbTableName)%%", "%%(self.keyFields[0].dbName)%%");
	}

	public function buildModelFromRow($row){
		if($row == null){
			return null;
		}
		$model = new %%(self.obName)%%_model();
		%%
allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$model->%(dbName)s = fromSQLDate($row['%(dbName)s']);" % { 'dbName' : field.dbName }
	else:
		attributeCode = "$model->%(dbName)s = $row['%(dbName)s'];" % { 'dbName' : field.dbName }
	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%
		return $model;
	}
	
	public function getUnique($db, $value){
		return parent::getUnique($db, '%%(self.keyFields[0].dbName)%%', $value);
	}
	
	public function deleteByKey($db, $value){
		return parent::deleteByKey($db, '%%(self.keyFields[0].dbName)%%', $value);
	}

	public function delete($db, $aModel){
		if( $aModel == null) {
			throw new Exception('Error while calling the [delete] method on a null object.');
		}
		return parent::deleteByKey($db, '%%(self.keyFields[0].dbName)%%', $aModel->%%(self.keyFields[0].dbName)%%);
	}

%%getterAll = ""
for field in self.fields:
	getter = ""
	if field.sqlType.upper()[0:4] == "FILE":
		continue
	else:
		getter = """
	/**
	 * Recupere la liste des enregistrements depuis le champ %(fieldName)s
	 * @param object $db database object
	 * @return array of data
	 */
	public function getAllBy_%(fieldName)s($db, $%(fieldName)s, $orderBy = null, $asc = null, $limit = null, $offset = null){
		return $this->getAllByCriteria($db, Array( new Criteria('%(fieldName)s', Criteria::$EQ, $%(fieldName)s) ), $orderBy, $asc, $limit, $offset);
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'obName' : self.obName,
		'fieldName' : field.dbName
	}
	getterAll += getter
RETURN = getterAll
%%

%%getterAll = ""
for field in self.fields:
	getter = ""
	if field.sqlType.upper()[0:7] == "VARCHAR" or field.sqlType.upper()[0:4] == "TEXT" :
		getter = """
	/**
	 * Recupere la liste des enregistrements depuis le champ %(fieldName)s
	 * @param object $db database object
	 * @return array of data
	 */
	public function getAllLike_%(fieldName)s($db, $%(fieldName)s, $orderBy = null, $asc = null, $limit = null, $offset = null){
		return $this->getAllByCriteria($db, Array( new Criteria('%(fieldName)s', Criteria::$LIKE, $%(fieldName)s) ), $orderBy, $asc, $limit, $offset);
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'fieldName' : field.dbName
	}
	getterAll += getter
RETURN = getterAll
%%


%%deleteAll = ""
for field in self.fields:
	deleteFct = ""
	if field.autoincrement:
		continue
	elif field.referencedObject:
		deleteFct = """
	/**
	 * Suppression d'un ensemble d'objets a partir d'une valeur qui n'est pas la cle
	 * @param object $db database object
	 * @return 
	 */
	public function deleteAllsBy_%(fieldName)s($db, $%(foreignKey)s){
		$allObjects = $this->getAllBy_%(fieldName)s($db, $%(foreignKey)s);
		foreach($allObjects as $object){
			$this->delete($db, $object);
		}
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'obName' : self.obName,
		'referencedObjectName' : field.referencedObject.obName,
		'foreignKey' : field.referencedObject.keyFields[0].dbName,
		'fieldName' : field.dbName
	}
	deleteAll += deleteFct
RETURN = deleteAll
%%


%%countAll = ""
for field in self.fields:
	countFct = ""
	if field.autoincrement:
		continue
	else:
		countFct = """
	/**
	 * Decompte d'un ensemble d'objets a partir d'une valeur de %(fieldName)s : %(desc)s
	 * @param object $db database object
	 * @return int
	 */
	public function countBy_%(fieldName)s($db, $%(fieldName)s){
		return $this->countByCriteria($db, Array( new Criteria('%(fieldName)s',Criteria::$EQ,$%(fieldName)s) ) );
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'obName' : self.obName,
		'fieldName' : field.dbName,
		'desc' : field.description
	}
	countAll += countFct
RETURN = countAll
%%


	/**
	 * 
	 * @param database_object $db
	 * @param %%(self.obName)%% $aModel
	 */
	public function insertNew($db, $aModel){
		$data=array( %%
allAttributesCode = ""
for field in self.fields:
	if field.autoincrement:
		continue
	attributeCode = ""
	if allAttributesCode != "":
		allAttributesCode += ","
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "'%(dbName)s'=>toSQLDate($aModel->%(dbName)s)" % { 'dbName' : field.dbName }
	else:
		attributeCode = "'%(dbName)s'=>emptyToNull($aModel->%(dbName)s)" % { 'dbName' : field.dbName }
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%);
		log_message('debug','[%%(self.obName)%%Service.php] : insert with data:'. print_r($data, true) );
		$db->insert($this->getTableName(), $data);
		$aModel->%%(self.keyFields[0].dbName)%% = $db->insert_id();
		return $aModel;
	}

	/**
	 *
	 * @param database_object $db
	 * @param %%(self.obName)%% $aModel
	 */
	public function update($db, $aModel) {
		$data = array(%%
allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if allAttributesCode != "":
		allAttributesCode += ","
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "'%(dbName)s'=>toSQLDate($aModel->%(dbName)s)" % { 'dbName' : field.dbName }
	else:
		attributeCode = "'%(dbName)s'=>emptyToNull($aModel->%(dbName)s)" % { 'dbName' : field.dbName }
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%);
		$db->where('%%(self.keyFields[0].dbName)%%', $aModel->%%(self.keyFields[0].dbName)%%);
		log_message('debug','[%%(self.obName)%%Service.php] : update with data:'. print_r($data, true) );
		$db->update($this->getTableName(), $data);
	}


	/***************************************************************************
	 * USER DEFINED FUNCTIONS
	 ***************************************************************************/
}

?>
