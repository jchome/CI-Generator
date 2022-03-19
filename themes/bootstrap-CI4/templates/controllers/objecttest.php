%[kind : unitTest]
%[file : %%(self.obName.title())%%test.php] 
%[path : Controllers/test]
<?php
/*
 * Created by generator
 *
 */
require_once(APPPATH . '/controllers/test/Toast.php');

class %%(self.obName.title())%%Test extends Toast {

	function __construct(){
		parent::__construct();
		$this->load->database('test');
		
		$this->load->library('%%(self.obName)%%Service');
		$this->load->model('%%(self.obName)%%Model');
		
	}
	
	/**
	 * OPTIONAL; Anything in this function will be run before each test
	 * Good for doing cleanup: resetting sessions, renewing objects, etc.
	 */
	function _pre() {
		$%%(self.obName.lower())%%s = $this->%%(self.obName.lower())%%service->getAll($this->db);
		foreach ($%%(self.obName.lower())%%s as $%%(self.obName.lower())%%) {
			$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
		}
	}
	
	
	/**
	 * OPTIONAL; Anything in this function will be run after each test
	 * I use it for setting $this->message = $this->MyModel->getError();
	 */
	function _post() {
		$%%(self.obName.lower())%%s = $this->%%(self.obName.lower())%%service->getAll($this->db);
		foreach ($%%(self.obName.lower())%%s as $%%(self.obName.lower())%%) {
			$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
		}
	}
	
	public function test_insert(){
		$this->message = "Tested methods: save, get%%(self.obName)%%, delete";
		// création d'un enregistrement
		$%%(self.obName.lower())%%_insert = new %%(self.obName)%%Model();
		%%
allAttributesCode = ""
index = 0
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		attributeCode = "// Nothing for field %s" % field.dbName
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'TODO';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = fromSQLDate('31/12/2050');" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "FLAG":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '0';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:5] == "COLOR":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '#ffffff';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'p4ssW0rD-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'file-%(index)d : ...';" % {	'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "$%(obName)s_insert->%(dbName)s = %(index)d;" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:7] == "VARCHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'test_%(index)d';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "TEXT":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'text-%(index)d : ...';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "CHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'c-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:9] == "TIMESTAMP":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	else:
		attributeCode = "//[ERROR] type [%s] not generated." % field.sqlType
		
	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
		$this->%%(self.obName.lower())%%service->insertNew($this->db, $%%(self.obName.lower())%%_insert);
		// $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%% est maintenant affecté
	
		$%%(self.obName.lower())%%_select = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%%);
	
		$this->_assert_equals($%%(self.obName.lower())%%_select->%%(self.keyFields[0].dbName)%%, $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%%);
		$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%_select->%%(self.keyFields[0].dbName)%%);
	}
	
	
	public function test_update(){
		$this->message = "Tested methods: save, update, get%%(self.obName)%%, delete";

		$%%(self.obName.lower())%%_insert = new %%(self.obName)%%Model();
		%%
allAttributesCode = ""
index = 0
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		attributeCode = "// Nothing for field %s" % field.dbName
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'TODO';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = fromSQLDate('31/12/2050');" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "FLAG":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '0';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:5] == "COLOR":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '#ffffff';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'p4ssW0rD-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'file-%(index)d : ...';" % {	'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "$%(obName)s_insert->%(dbName)s = %(index)d;" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:7] == "VARCHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'test_%(index)d';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "TEXT":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'text-%(index)d : ...';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "CHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'c-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }

	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
		$this->%%(self.obName.lower())%%service->insertNew($this->db, $%%(self.obName.lower())%%_insert);
	
		$%%(self.obName.lower())%%_update = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%%);
		%%
allAttributesCode = ""
index = 0
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		attributeCode = "// Nothing for field %s" % field.dbName
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "$%(obName)s_update->%(dbName)s = 'TODO';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$%(obName)s_update->%(dbName)s = fromSQLDate('31/01/2051');" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "FLAG":
		attributeCode = "$%(obName)s_update->%(dbName)s = '1';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:5] == "COLOR":
		attributeCode = "$%(obName)s_update->%(dbName)s = '#fffff1';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode = "$%(obName)s_update->%(dbName)s = 'pwd1-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper() == "FILE":
		attributeCode = "$%(obName)s_update->%(dbName)s = 'file1-%(index)d : ...';" % {	'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "$%(obName)s_update->%(dbName)s = 9%(index)d;" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:7] == "VARCHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_update->%(dbName)s = 'test1_%(index)d';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "TEXT":
		# TODO : taille limite
		attributeCode = "$%(obName)s_update->%(dbName)s = 'text1-%(index)d : ...';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "CHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_update->%(dbName)s = 'b-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }

	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
		$this->%%(self.obName.lower())%%service->update($this->db, $%%(self.obName.lower())%%_insert);
	
		$%%(self.obName.lower())%%_update = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%%);
		
		%%
RETURN = self.dbVariablesList("""if(!$this->_assert_equals($%s_insert->(instVar)s, $%s_update->(instVar)s)) {
			return false;
		}""" % (self.obName.lower(), self.obName.lower()), 'instVar',  'typeVar', 'descrVar', 2, includesKey=True)
%%

		$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%%);
	}
	
	
	public function test_count(){
		$this->message = "Tested methods: count, save, getUnique, deleteByKey";
	
		// comptage pour vérification : avant
		$count%%(self.obName)%%sAvant = $this->%%(self.obName.lower())%%service->count($this->db);
	
		// création d'un enregistrement
		$%%(self.obName.lower())%% = new %%(self.obName)%%Model();
		%%
allAttributesCode = ""
index = 0
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		attributeCode = "// Nothing for field %s" % field.dbName
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "$%(obName)s->%(dbName)s = 'TODO';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$%(obName)s->%(dbName)s = fromSQLDate('31/12/2050');" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "FLAG":
		attributeCode = "$%(obName)s->%(dbName)s = '0';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:5] == "COLOR":
		attributeCode = "$%(obName)s->%(dbName)s = '#ffffff';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode = "$%(obName)s->%(dbName)s = 'p4ssW0rD-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper() == "FILE":
		attributeCode = "$%(obName)s->%(dbName)s = 'file-%(index)d : ...';" % {	'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "$%(obName)s->%(dbName)s = %(index)d;" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:7] == "VARCHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s->%(dbName)s = 'test_%(index)d';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "TEXT":
		# TODO : taille limite
		attributeCode = "$%(obName)s->%(dbName)s = 'text-%(index)d : ...';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "CHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s->%(dbName)s = 'c-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }

	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
		$this->%%(self.obName.lower())%%service->insertNew($this->db, $%%(self.obName.lower())%%);
	
		// comptage pour vérification : après insertion
		$count%%(self.obName)%%sApres = $this->%%(self.obName.lower())%%service->count($this->db);
	
		// verification d'ajout d'un enregistrement
		$this->_assert_equals($count%%(self.obName)%%sAvant +1, $count%%(self.obName)%%sApres);
	
		// recupération de l'objet par son  %%(self.keyFields[0].dbName)%%
		$%%(self.obName.lower())%% = $this->%%(self.obName.lower())%%service->getUnique($this->db, $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
	
		// suppression de l'enregistrement
		$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
	
		// comptage pour vérification : après suppression
		$count%%(self.obName)%%sFinal = $this->%%(self.obName.lower())%%service->count($this->db);
		$this->_assert_equals($count%%(self.obName)%%sAvant, $count%%(self.obName)%%sFinal);
	
	}
	
	
	function test_list(){
		$this->message = "Tested methods: save, getAll, delete";
	
		$%%(self.obName.lower())%%_insert = new %%(self.obName)%%Model();
		%%
allAttributesCode = ""
index = 0
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		attributeCode = "// Nothing for field %s" % field.dbName
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'TODO';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "DATE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = fromSQLDate('31/12/2050');" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "FLAG":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '0';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:5] == "COLOR":
		attributeCode = "$%(obName)s_insert->%(dbName)s = '#ffffff';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'p4ssW0rD-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper() == "FILE":
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'file-%(index)d : ...';" % {	'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:3] == "INT":
		attributeCode = "$%(obName)s_insert->%(dbName)s = %(index)d;" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:7] == "VARCHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'test_%(index)d';" % { 'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "TEXT":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'text-%(index)d : ...';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }
	elif field.sqlType.upper()[0:4] == "CHAR":
		# TODO : taille limite
		attributeCode = "$%(obName)s_insert->%(dbName)s = 'c-%(index)d';" % {'obName' : self.obName.lower(), 'dbName' : field.dbName, 'index': index }

	if allAttributesCode != "":
		allAttributesCode += "\n\t\t"
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
		$this->%%(self.obName.lower())%%service->insertNew($this->db, $%%(self.obName.lower())%%_insert);
	
		$%%(self.obName.lower())%%s = $this->%%(self.obName.lower())%%service->getAll($this->db);
		if( ! $this->_assert_not_empty($%%(self.obName.lower())%%s) ) {
			log_message('DEBUG', '[UNIT TEST / %%(self.obName.title())%%test.php)] #test_list : getAll after insert != 1');
			return $this->_fail('getAll after insert != 1');
		}
		$found = 0;
		foreach ($%%(self.obName.lower())%%s as $%%(self.obName.lower())%%) {
			if($%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%% == $%%(self.obName.lower())%%_insert->%%(self.keyFields[0].dbName)%% &&
					%%
RETURN = self.dbVariablesList("""$this->_assert_equals($%s->(instVar)s, $%s_insert->(instVar)s )""" % (self.obName.lower(), self.obName.lower()), 'instVar',  'typeVar', 'descrVar', 5, includesKey=False, suffix=" && ")
%%
				){
				$found++;
			}
		}
		if( $found == 1 ){
			$this->%%(self.obName.lower())%%service->deleteByKey($this->db, $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
			log_message('DEBUG', '[UNIT TEST / %%(self.obName.title())%%test.php)] #test_list : OK');
			return $this->_assert_true(True);
		}else{
			log_message('DEBUG', '[UNIT TEST / %%(self.obName.title())%%test.php)] #test_list : found != 1');
			return $this->_fail('found != 1');
		}
	}

}
?>
