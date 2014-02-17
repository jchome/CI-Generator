%[kind : baseModels]
%[file : %%(self.obName.lower())%%Base_model.php]
%[path : models]
<?php
/*
 * Created by generator
 *
 */

/***************************************************************************
 * DO NOT MODIFY THIS FILE, IT IS GENERATED
 ***************************************************************************/

abstract class %%(self.obName)%%Base_model extends CI_Model {
	
	%%
RETURN = self.dbVariablesList("""/**
\t* (descrVar)s
\t* @var (typeVar)s
\t*/
\tvar $(instVar)s;
""", 'instVar',  'typeVar', 'descrVar', 1)
%%
	
	/**
	 * Constructeur
	 */
	function __construct(){
		parent::__construct();
		$this->load->helper('%%(self.obName.lower())%%');
		// utils for date management
		$this->load->helper('utils');
		
	}
	
	/************************************************************************
	 * Methodes de mise a jour a partir de la base de donnees
	 ************************************************************************/

	/**
	 * Cree une nouvelle instance a partir d'un enregistrement de base de donnees
	 */
	static function %%(self.obName)%%_modelFromRow($row){
		$model = new %%(self.obName)%%_model();
		%%
allAttributesCode = ""
for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper() == "DATE":
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
	static function getAll%%(self.obName)%%s($db, $orderBy = null, $asc = null, $limit = null, $offset = null){
		$rows = getAll%%(self.obName)%%sFromDB($db, $orderBy, $asc, $limit, $offset);
		$records = array();
		foreach ($rows as $row) {
			$records[%%allKeys = ""
for aKey in self.keyFields:
	if allKeys != "":
		allKeys += ","
	allKeys += "$row['%s']" % aKey.dbName
RETURN = allKeys
%%] = %%(self.obName)%%_model::%%(self.obName)%%_modelFromRow($row);
		}
		return $records;
	}
	
	/**
	 * recupere le nombre d'enregistrements
	 * @param $db connexion a la base de donnees
	 */
	static function getCount%%(self.obName)%%s($db){
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
	static function delete($db, %%allKeys = ""
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
		if field.sqlType.upper() == "DATE":
			attributeCode = "toSQLDate($this->%(dbName)s)" % { 'dbName' : field.dbName }
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
	if field.sqlType.upper() == "DATE":
		attributeCode = "toSQLDate($this->%(dbName)s)" % { 'dbName' : field.dbName }
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

	
	/***************************************************************************
	 * DO NOT MODIFY THIS FILE, IT IS GENERATED
	 ***************************************************************************/

}

?>
