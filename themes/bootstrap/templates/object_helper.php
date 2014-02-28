%[kind : helpers]
%[file : %%(self.obName.lower())%%_helper.php] 
%[path : helpers]
<?php

/*
 * Created by generator
 *
 */

/**
 * Recupere la liste des enregistrements
 * @param object $db database object
 * @return array of data
 */
if (!function_exists('getAll%%(self.obName)%%sFromDB')) {
	function getAll%%(self.obName)%%sFromDB($db, $orderBy = null, $asc = null, $limit = null, $offset = null) {
		if( $orderBy != null ){
			if($asc != null) {
				$db->order_by($orderBy, $asc);
			}else {
				$db->order_by($orderBy, "asc");
			}
		}
		if( $limit == null ) {
			$query = $db->get("%%(self.dbTableName)%%");
		} else {
			$query = $db->limit($limit, $offset)->get("%%(self.dbTableName)%%");
		}
		// recuperer les enregistrements
		$records = array();
		foreach ($query->result_array() as $row) {
			$records[] = $row;
		}
		return $records;
	}
}

/**
 * Recupere le nombre d'enregistrements
 * @param object $db database object
 * @return int
 */
if (!function_exists('getCount%%(self.obName)%%sFromDB')) {
	function getCount%%(self.obName)%%sFromDB($db) {
		return $db->count_all("%%(self.dbTableName)%%");
	}
}

/**
 * Insere un nouvel enregistrement
 */
if (!function_exists('insertNew%%(self.obName)%%')) {
	function insertNew%%(self.obName)%%($db, %%
includesAutoIncrement = False
for field in self.keyFields:
	if field.autoincrement and not includesAutoIncrement:
		includesAutoIncrement = True
RETURN = self.dbVariablesList("$(var)s", 'var',  '', '', 0, not includesAutoIncrement)
%%) {
		$data=array( %%
includesAutoIncrement = False
for field in self.keyFields:
	if field.autoincrement and not includesAutoIncrement:
		includesAutoIncrement = True
RETURN = self.dbVariablesList("'(var)s'=>$(var)s", 'var',  '', '', 0, not includesAutoIncrement)
%%);
		log_message('debug','[%%(self.obName.lower())%%_helper.php] : insertNew%%(self.obName)%% with data:'. print_r($data, true) );
		$db->insert('%%(self.dbTableName)%%',$data);
		return $db->insert_id();
	}
}


/**
 * Mise a jour d'un enregistrement
 */
if (!function_exists('update%%(self.obName)%%')) {
	function update%%(self.obName)%%($db, %%(self.listOfKeys(fieldPrefix="$", fieldSuffix = ", "))%% %%
includesKey = False
RETURN = ", " + self.dbVariablesList("$(var)s", 'var',  '', '', 0, includesKey)
%%) {
		$data = array(%%
includesKey = False
RETURN = self.dbVariablesList("'(var)s'=>$(var)s", 'var',  '', '', 0, includesKey)
%%);
		$db->where('%%(self.keyFields[0].dbName)%%', %%(self.listOfKeys(fieldPrefix="$", fieldSuffix = ", "))%%);
		log_message('debug','[%%(self.obName.lower())%%_helper.php] : update%%(self.obName)%% with data:'. print_r($data, true) );
		$db->update('%%(self.dbTableName)%%', $data);
	}
}


/**
 * Suppression d'un enregistrement
 */
if (!function_exists('delete%%(self.obName)%%')) {
	function delete%%(self.obName)%%($db, %%(self.listOfKeys(fieldPrefix="$", fieldSuffix = ", "))%%) {
		$db->delete('%%(self.dbTableName)%%', array(%%
allVariables = ""
for field in self.keyFields:
	if allVariables != "":
		allVariables += ", "
	allVariables += """'%(variable)s'=>$%(variable)s""" % {'variable' : field.dbName }
RETURN = allVariables
%%)); 
	}
}


/**
 * Recupere les informations d'un enregistrement
 * @param object $db database object
 * @param int id de l'enregistrement
 * @return array
 */
if (!function_exists('get%%(self.obName)%%Row')) {
	function get%%(self.obName)%%Row($db, %%(self.listOfKeys(fieldPrefix="$", fieldSuffix = ", "))%%) {
		$query = $db->get_where('%%(self.dbTableName)%%', array('%%(self.keyFields[0].dbName)%%' => $%%(self.keyFields[0].dbName)%%));
		if ($query->num_rows() != 1) {
			return null;
		}
		return $query->row_array();
	}
}


%%getterAll = ""
for field in self.fields:
	getter = ""
	if field.autoincrement:
		continue
	elif field.referencedObject:
		getter = """
/**
 * Recupere la liste des enregistrements depuis la cle etrangere %(obName)s->%(fieldName)s ==> %(referencedObjectName)s->%(foreignKey)s
 * @param object $db database object
 * @return array of data
 */
if (!function_exists('getAll%(obName)ssFor%(referencedObjectName)sFromDBBy_%(fieldName)s')) {
	function getAll%(obName)ssFor%(referencedObjectName)sFromDBBy_%(fieldName)s($db, $%(foreignKey)s, $orderBy = null, $asc = null, $limit = null, $offset = null) {
		if( $orderBy != null ){
			if($asc != null) {
				$db->order_by($orderBy, $asc);
			}else {
				$db->order_by($orderBy, "asc");
			}
		}
		if( $limit == null ) {
			$query = $db->get_where("%(tableName)s", array('%(fieldName)s' => $%(foreignKey)s));
		} else {
			$query = $db->limit($limit, $offset)->get_where("%(tableName)s", array('%(fieldName)s' => $%(foreignKey)s));
		}
		// recuperer les enregistrements
		$records = array();
		foreach ($query->result_array() as $row) {
			$records[] = $row;
		}
		return $records;
	}
}
""" % { 'tableName' : self.dbTableName,
		'obName' : self.obName,
		'referencedObjectName' : field.referencedObject.obName,
		'foreignKey' : field.referencedObject.keyFields[0].dbName,
		'fieldName' : field.dbName
	}
	else:
		if field.sqlType.upper()[0:4] == "FILE":
			continue
		getter = """
/**
 * Recupere la liste des enregistrements depuis une valeur du champ %(fieldName)s
 * @param object $db database object
 * @return array of data
 */
if (!function_exists('getAll%(obName)ssFromDBBy_%(fieldName)s')) {
	function getAll%(obName)ssFromDBBy_%(fieldName)s($db, $%(fieldName)s, $limit = null, $offset = null) {
		$query = $db->from("%(tableName)s")->limit($limit, $offset)->like('%(fieldName)s', $%(fieldName)s)->get();
		// recuperer les enregistrements
		$records = array();
		foreach ($query->result_array() as $row) {
			$records[] = $row;
		}
		return $records;
	}
}
""" % { 'tableName' : self.dbTableName,
		'obName' : self.obName,
		'fieldName' : field.dbName
	}
	getterAll += getter
RETURN = getterAll
%%


	/***************************************************************************
	 * USER DEFINED FUNCTIONS
	 ***************************************************************************/


?>
