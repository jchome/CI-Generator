<?php
abstract class DAOService{
	
	/**
	 * Name of the query table in database
	 * @var string
	 */
	var $tableName;
	
	function __construct($aTableName = null, $aKeyField = null){
		$this->tableName = $aTableName;
		$this->keyField = $aKeyField;
	}
	
	public function getTableName(){
		return $this->tableName;
	}
	
	public function getKeyField(){
		return $this->keyField;
	}
	
	public function buildModelFromRow($row){
		throw new Exception('The class method [buildModelFromRow] is not defined.');
	}
	
	/**
	 * Recupération des données pour un ensemble de critères
	 * @param databse_object $db
	 * @param string $orderBy
	 * @param string $asc
	 * @param int $limit
	 * @param int $offset
	 * @return array of Models
	 * @throws Exception
	 */
	public function getAllByCriteria($db, $criteriaArray, $orderBy = null, $asc = null, $limit = null, $offset = null){
		if( $this->getTableName() == null ){
			 throw new Exception('The variable [tableName] is not defined.');
		}
		
		$db->select('*');
		$db->from($this->getTableName());
		if( $orderBy != null ){
			if($asc != null) {
				$db->order_by($orderBy, $asc);
			}else {
				$db->order_by($orderBy, "asc");
			}
		}
		log_message('debug',"Call #getAllByCriteria from ". $this->getTableName() ." with parameters:\n".
				"  - orderBy : " . $orderBy . "\n".
				"  - asc : " . $asc . "\n".
				"  - limit : " . $limit . "\n".
				"  - offset : " . $offset . "\n"
				);
		if($limit != null){
			$db->limit($limit, $offset);
		}
		
		$queryString = $this->_readCriteriaArray($db, $criteriaArray, /*CriteriaOperator::$AND*/ 'AND' );
		
		$query = $db->get();
		log_message('debug','Query : ' . $queryString);
		log_message('debug','LAST Query : ' . $db->last_query() );
		
		// recuperer les enregistrements
		$records = array();
		foreach ($query->result_array() as $row) {
			$records[$row[$this->getKeyField()]] = $this->buildModelFromRow($row);
		}
		return $records;
		
	}
	
	private function applyWhere($db, $operator, $clause1, $clause2 = null){
		if($operator == 'AND'){
			$db->where($clause1, $clause2);
		}else{
			$db->or_where($clause1, $clause2);
		}
	}
	private function applyLike($db, $operator, $clause1, $clause2 = null){
		if($operator == 'AND'){
			$db->like($clause1, $clause2, 'none', FALSE);
		}else{
			$db->or_like($clause1, $clause2, 'none', FALSE);
		}
	}
	
	private function _readCriteriaArray($db, $criteriaArray, $operator = /*CriteriaOperator::$AND*/ 'AND' ){
		$queryString = "";
		foreach($criteriaArray as $criteria){
			if(get_class($criteria) == "Criteria"){
				if( $criteria->operator == Criteria::$EQ ){
					$this->applyWhere($db, $operator, $criteria->column, $criteria->value);
					$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
				}else if($criteria->operator == Criteria::$LIKE){
					$this->applyLike($db, $operator, $criteria->column, $criteria->value);
					$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
				}else if($criteria->operator == Criteria::$DIFF ||
						$criteria->operator == Criteria::$GE ||
						$criteria->operator == Criteria::$GT ||
						$criteria->operator == Criteria::$LE ||
						$criteria->operator == Criteria::$LT){
							$this->applyWhere($db, $operator, $criteria->column. ' '. $criteria->operator, $criteria->value);
							$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
				}else if($criteria->operator == Criteria::$NULL ||
						$criteria->operator == Criteria::$NOTNULL){
							$this->applyWhere($db, $operator, $criteria->column. ' '. $criteria->operator);
							$queryString .= $criteria->column . " " .$criteria->operator . "\n";
				}else if($criteria->operator == Criteria::$JOIN || 
						$criteria->operator == Criteria::$JOIN_LEFT_OUTER) {
					if(strpos($criteria->value,'.') == FALSE){
						throw new Exception('The value of this criteria must contain a "." character, in <' . $criteria->value . '>');
					}
					$valueSplitedArray = explode('.', $criteria->value);
					$joinTable = $valueSplitedArray[0];
					if($criteria->operator == Criteria::$JOIN){
						$db->join($joinTable, $criteria->value .' = '. /*$this->getTableName() .'.'.*/ $criteria->column);
					}else{
						$db->join($joinTable, $criteria->value .' = '. /*$this->getTableName() .'.'.*/ $criteria->column, 'left');
					}
					$queryString .= $criteria->operator .' '. $criteria->value .' = '. /*$this->getTableName() .'.'.*/ $criteria->column . "\n";
				}
			}else if(get_class($criteria) == "CriteriaOperator"){
				$db->group_start();
				$queryString .= " ( ";
				$queryString .= $this->_readCriteriaArray($db, Array($criteria->criteria1) );
				$queryString .= $this->_readCriteriaArray($db, Array($criteria->criteria2), $criteria->operator);
				$queryString .= " ) ";
				$db->group_end();
			}
		}
		return $queryString;
	}
	
	/**
	 * Recupération des données complètes
	 * @param database_object $db
	 * @param string $orderBy
	 * @param string $asc
	 * @param int $limit
	 * @param int $offset
	 * @return array of Models
	 */
	public function getAll($db, $orderBy = null, $asc = null, $limit = null, $offset = null){
		return $this->getAllByCriteria($db, Array(), $orderBy, $asc, $limit, $offset);
	}
	
	public function count($db){
		return $this->countByCriteria($db, Array());
	}
	/**
	 * 
	 * @param database_object $db
	 * @param array $criteriaArray
	 * @return int
	 * @throws Exception
	 */
	public function countByCriteria($db, $criteriaArray){
		if( $this->getTableName() == null ){
			throw new Exception('The variable [tableName] is not defined.');
		}
		log_message('debug',"Call #countByCriteria from ". $this->getTableName() );
		
		$db->select('*');
		$db->from($this->getTableName());
		
		$queryString = "";
		foreach($criteriaArray as $criteria){
			if( $criteria->operator == Criteria::$EQ ){
				$db->where($criteria->column, $criteria->value);
				$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
			}else if($criteria->operator == Criteria::$LIKE){
				$db->like($criteria->column, $criteria->value);
				$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
			}else if($criteria->operator == Criteria::$DIFF ||
					$criteria->operator == Criteria::$GE ||
					$criteria->operator == Criteria::$GT ||
					$criteria->operator == Criteria::$LE ||
					$criteria->operator == Criteria::$LT){
				$db->where($criteria->column. ' '. $criteria->operator, $criteria->value);
				$queryString .= $criteria->column . " " .$criteria->operator . " " . $criteria->value . "\n";
			}else if($criteria->operator == Criteria::$NULL ||
					$criteria->operator == Criteria::$NOTNULL){
				$db->where($criteria->column. ' '. $criteria->operator);
				$queryString .= $criteria->column . " " .$criteria->operator . "\n";
			}else if($criteria->operator == Criteria::$JOIN){
				if(strpos($criteria->value,'.') == FALSE){
					throw new Exception('The value of this criteria must contain a "." character, in <' . $criteria->value . '>');
				}
				$valueSplitedArray = explode('.', $criteria->value);
				$joinTable = $valueSplitedArray[0];
				$db->join($joinTable, $criteria->value .' = '. $this->getTableName() .'.'. $criteria->column);
				$queryString .= 'JOIN '. $criteria->value .' = '. $this->getTableName() .'.'. $criteria->column . "\n";
			}
		}
		log_message('debug','Query : ' . $queryString);
		$query = $db->get();
		return $query->num_rows();
	}
	
	protected function getUniqueKeyValue($db, $key, $value){
		if( $this->getTableName() == null ){
			throw new Exception('The variable [tableName] is not defined.');
		}
		$query = $db->get_where($this->getTableName(), array($key => $value));
		if ($query->num_rows() != 1) {
			return null;
		}
		return $this->buildModelFromRow($query->row_array());
	}
	
	
	protected function deleteByKeyValue($db, $key, $value){
		if( $this->getTableName() == null ){
			throw new Exception('The variable [tableName] is not defined.');
		}
		
		$db->delete($this->getTableName(), array($key=>$value));
		
	}
		
}
?>