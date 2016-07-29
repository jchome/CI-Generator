%[kind : models]
%[file : %%(self.obName.title())%%_model.php]
%[path : models]
<?php
/*
 * Created by generator
 *
 */

/***************************************************************************
 * DO NOT MODIFY THIS FILE, IT IS GENERATED
 ***************************************************************************/

class %%(self.obName.title())%%_model extends CI_Model {
	
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
		$this->load->helper('utils');
		$this->load->helper('criteria');
	}
	
	
	/***************************************************************************
	 * DO NOT MODIFY THIS FILE, IT IS GENERATED
	 ***************************************************************************/

}

?>
