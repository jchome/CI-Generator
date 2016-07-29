<?php

class Criteria {
	static $EQ = "=";
	static $LIKE = "like";
	static $LT = "<";
	static $LE = "<=";
	static $GT = ">";
	static $GE = ">=";
	
	static $NULL = "is null";
	static $NOTNULL = "is not null";
	static $DIFF = "!=";
	
	static $JOIN = "JOIN";
	static $JOIN_LEFT_OUTER = "JOIN_LEFT_OUTER";
	
	var $column;
	var $operator;
	var $value;
	
	function __construct($aColumn, $anOperator, $aValue){
		$this->column = $aColumn;
		$this->operator = $anOperator;
		$this->value = $aValue;
	}
	
}



?>