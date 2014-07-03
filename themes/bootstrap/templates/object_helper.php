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

/**
 * Recupere un ensemble d'enregistrements qui correspondent aux criteres fournis
 * @param object $db database object
 * @param array de Criteria
 * @return array
 */
if (!function_exists('getAll%%(self.obName)%%sByCrietriaFromDB')) {
	function getAll%%(self.obName)%%sByCrietriaFromDB($db, $criteriaArray, $orderBy = null, $asc = null, $limit = null, $offset = null) {
		$db->select('*');
		$db->from("%%(self.dbTableName)%%");
		if( $orderBy != null ){
			if($asc != null) {
				$db->order_by($orderBy, $asc);
			}else {
				$db->order_by($orderBy, "asc");
			}
		}
		if($limit != null){
			$db->limit($limit, $offset);
		}
		foreach($criteriaArray as $criteria){
			if( $criteria->operator == Criteria::$EQ ){
				$db->where($criteria->column, $criteria->value);
			}else if($criteria->operator == Criteria::$LIKE){
				$db->like($criteria->column, $criteria->value);
			}else if($criteria->operator == Criteria::$DIFF || 
					$criteria->operator == Criteria::$GE ||
					$criteria->operator == Criteria::$GT ||
					$criteria->operator == Criteria::$LE ||
					$criteria->operator == Criteria::$LT){
				$db->where($criteria->column. ' '. $criteria->operator, $criteria->value);
			}else if($criteria->operator == Criteria::$NULL){
				$db->where($criteria->column. ' '. $criteria->operator);
			}
		}
		$query = $db->get();

		// recuperer les enregistrements
		$records = array();
		foreach ($query->result_array() as $row) {
			$records[] = $row;
		}
		return $records;
	}
}

/**
 * Recupere le nombre d'enregistrements qui correspondent aux criteres fournis
 * @param object $db database object
 * @param array de Criteria
 * @return array
 */
 if (!function_exists('count%%(self.obName)%%sByCrietriaFromDB')) {
	function count%%(self.obName)%%sByCrietriaFromDB($db, $criteriaArray) {
		$db->select('*');
		$db->from("%%(self.dbTableName)%%");
		foreach($criteriaArray as $criteria){
			if( $criteria->operator == Criteria::$EQ ){
				$db->where($criteria->column, $criteria->value);
			}else if($criteria->operator == Criteria::$LIKE){
				$db->like($criteria->column, $criteria->value);
			}else if($criteria->operator == Criteria::$DIFF || 
					$criteria->operator == Criteria::$GE ||
					$criteria->operator == Criteria::$GT ||
					$criteria->operator == Criteria::$LE ||
					$criteria->operator == Criteria::$LT){
				$db->where($criteria->column. ' '. $criteria->operator, $criteria->value);
			}else if($criteria->operator == Criteria::$NULL){
				$db->where($criteria->column. ' '. $criteria->operator);
			}
		}
		$query = $db->get();
		return $query->num_rows();
	}
}


	/***************************************************************************
	 * USER DEFINED FUNCTIONS
	 ***************************************************************************/


?>
