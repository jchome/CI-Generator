%[kind : models]
%[file : %%(self.obName.lower())%%_model.php]
%[path : models]
<?php
/*
 * Created by generator
 *
 */

/***************************************************************************
 * DO NOT MODIFY THIS FILE, IT IS GENERATED
 ***************************************************************************/

class %%(self.obName)%%_model extends CI_Model {
	
	%%
RETURN = self.dbVariablesList("""/**
\t* (descrVar)s
\t* @var (typeVar)s
\t*/
\tvar $(instVar)s;
""", 'instVar',  'typeVar', 'descrVar', 1)
%%

	%%
allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "static $liste_%(dbName)s = array(" % { 'dbName' : field.dbName }
		enumTypes = field.sqlType[5:-1]
		typeList = ""
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			typeList += """"%(value)s"=>"%(text)s",""" % {'value': valueAndText[0].strip(), 'text': valueAndText[1].strip()}
		typeList = typeList[:-1]
		attributeCode += typeList + ");\n\t"
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%
	
	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->helper('%%(self.obName.lower())%%');
		// utils for date management
		$this->load->helper('utils');
		$this->load->helper('criteria');
		
	}
	
	/************************************************************************
	 * Methodes de mise a jour a partir de la base de donnees
	 ************************************************************************/

	/**
	 * Cree une nouvelle instance a partir d'un enregistrement de base de donnees
	 */
	public static function %%(self.obName)%%_modelFromRow($row){
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

	/**
	 * recupere tous les enregistrements
	 * @param $db connexion a la base de donnees
	 */
	public static function getAll%%(self.obName)%%s($db, $orderBy = null, $asc = null, $limit = null, $offset = null){
		return self::getAllByCrietria($db, Array(), $orderBy, $asc, $limit, $offset);
	}
	
	/**
	 * recupere le nombre d'enregistrements
	 * @param $db connexion a la base de donnees
	 */
	public static function getCount%%(self.obName)%%s($db){
		return getCount%%(self.obName)%%sFromDB($db);
	}
	
	/**
	 * Recupere l'enregistrement a partir de son id
	 * @param $db connexion a la base de donnees
	 * @param %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%% identifiant de l'enregistrement a recuperer
	 */
	static function get%%(self.obName)%%($db, %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%%){
		$row = get%%(self.obName)%%Row($db, %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%%);
		return %%(self.obName)%%_model::%%(self.obName)%%_modelFromRow($row);
	}
	
	/**
	 * Suppression d'un enregistrement
	 * @param $db connexion a la base de donnees
	 * @param %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%% identifiant de l'enregistrement a supprimer
	 */
	public static function delete($db, %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%%){
		delete%%(self.obName)%%($db, %%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ", "
	allKeys += "$%s" % aKey.dbName
RETURN = allKeys%%);
	}

	/**
	 * Enregistre en base un nouvel enregistrement
	 * @param $db connexion a la base de donnees
	 */
	public function save($db){
		$this->%%(self.keyFields[0].dbName)%% = insertNew%%(self.obName)%%($db, %%allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if not field.autoincrement :
		if field.sqlType.upper()[0:4] == "DATE":
			attributeCode = "toSQLDate($this->%(dbName)s)" % { 'dbName' : field.dbName }
		elif field.sqlType.upper()[0:3] == "INT":
			attributeCode = "emptyToNull($this->%(dbName)s)" % { 'dbName' : field.dbName }
		else:
			attributeCode = "$this->%(dbName)s" % { 'dbName' : field.dbName }
	if allAttributesCode != "":
		allAttributesCode += ", "
	allAttributesCode += attributeCode
RETURN = allAttributesCode%%);
	}

	/**
	 * Mise a jour des donnees d'un enregistrement
	 * @param $db connexion a la base de donnees
	 */
%%if self.isCrossTable and len(self.nonKeyFields) == 0:
	RETURN = "/* DO NOT REMOVE THESE COMMENT"
else:
	RETURN = ""%%
	public function update($db){
		update%%(self.obName)%%($db, %%allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "toSQLDate($this->%(dbName)s)" % { 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "emptyToNull($this->%(dbName)s)" % { 'dbName' : field.dbName }
	else:
		attributeCode = "$this->%(dbName)s" % { 'dbName' : field.dbName }
	if allAttributesCode != "":
		allAttributesCode += ", "
	allAttributesCode += attributeCode
RETURN = allAttributesCode%%);
	}
%%if self.isCrossTable and len(self.nonKeyFields) == 0:
	RETURN = "* /DO NOT REMOVE THESE COMMENT */"
else:
	RETURN = ""%%

	public static function getAllByCrietria($db, $criteriaArray, $orderBy = null, $asc = null, $limit = null, $offset = null){
		$rows = getAll%%(self.obName)%%sByCrietriaFromDB($db, $criteriaArray, $orderBy, $asc, $limit, $offset);
		$records = array();
		foreach ($rows as $row) {
			$records[%%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += " . '@' . "
	allKeys += "$row['%s']" % aKey.dbName
RETURN = allKeys
%%] = %%(self.obName)%%_model::%%(self.obName)%%_modelFromRow($row);
		}
		return $records;
	}

	public static function countByCrietria($db, $criteriaArray){
		return count%%(self.obName)%%sByCrietriaFromDB($db, $criteriaArray);
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
	public static function getAll%(obName)ssBy_%(fieldName)s($db, $%(fieldName)s, $orderBy = null, $asc = null, $limit = null, $offset = null){
		return self::getAllByCrietria($db, Array( new Criteria('%(fieldName)s', Criteria::$EQ, $%(fieldName)s) ), $orderBy, $asc, $limit, $offset);
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'obName' : self.obName,
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
	static function deleteAll%(obName)ssBy_%(fieldName)s($db, $%(foreignKey)s){
		$allObjects = %(obName)s_model::getAll%(obName)ssFor%(referencedObjectName)sBy_%(fieldName)s($db, $%(foreignKey)s);
		foreach($allObjects as $object){
			%(obName)s_model::delete($db, $object->%(keyField)s);
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
	public static function getCount%(obName)ssBy_%(fieldName)s($db, $%(fieldName)s){
		return self::countByCrietria($db, Array( new Criteria('%(fieldName)s',Criteria::$EQ,$%(fieldName)s) ) );
	}
""" % { 'keyField' : self.keyFields[0].dbName,
		'obName' : self.obName,
		'fieldName' : field.dbName,
		'desc' : field.description
	}
	countAll += countFct
RETURN = countAll
%%

	/***************************************************************************
	 * DO NOT MODIFY THIS FILE, IT IS GENERATED
	 ***************************************************************************/

}

?>
