%[kind : sql]
%[file : cretab_%%(self.obName.lower())%%.sql]
%[path : ../../Assets/Database/sql]
/**
 * Script MySQL pour %%(self.obName)%%
 * %%foreignKeys = ""
for field in self.fields:
	foreignKey = ""
	if field.referencedObject:
		foreignKey = "\n\t- cretab_%s.sql" % field.referencedObject.obName.lower()
	foreignKeys += foreignKey
if foreignKeys != "":
	foreignKeys = "Depend de :" + foreignKeys
RETURN = foreignKeys
%%
**/

CREATE TABLE `%%(DATABASE + "_" if DATABASE != "" else "")%%%%(self.dbTableName)%%` (
%%content = ""
allAttributesCode = ""

for field in self.fields:
	if allAttributesCode != "":
		allAttributesCode += ", \n"

	typeForSQL = field.sqlType

	if field.sqlType.upper()[0:4] == "FLAG":
		typeForSQL = "tinyint(1)"
	elif field.sqlType.upper()[0:5] == "COLOR":
		typeForSQL = "char(7)"
	elif field.sqlType.upper()[0:5] == "FLOAT":
		if len(field.sqlType) == 5:
			typeForSQL = "float"
		else:
			typeForSQL = "float(%s)" % field.sqlType[6:-1]
	elif field.sqlType.upper()[0:4] == "DATE":
		typeForSQL = "date"
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		typeForSQL = "varchar" + field.sqlType[8:]
	elif field.sqlType.upper()[0:4] == "ENUM":
		typeForSQL = "ENUM(" 
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			typeForSQL += """'%(value)s',""" % {'value': valueAndText[0].strip()}
		typeForSQL = typeForSQL[:-1]
		typeForSQL += ")"

	elif field.sqlType.upper()[0:4] == "FILE":
		typeForSQL = "varchar(4000)" 

	attributeCode = "\t`%(dbName)s` %(sqlType)s " % { 'dbName' : field.dbName,
	  'sqlType' : typeForSQL
	}
	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		attributeCode += "NOT NULL "
	if field.autoincrement:
		attributeCode += "AUTO_INCREMENT "
	attributeCode += "COMMENT '%(desc)s'" % { 'desc' : field.obName.replace("'","\\'") }
	allAttributesCode += attributeCode

content += allAttributesCode

primaryKeys = ""
for field in self.keyFields:
	primaryKeys += """ ,
	PRIMARY KEY (%s) """ % field.dbName

content += primaryKeys + """
);"""

RETURN = content
%%


%%foreignKeys = ""
for field in self.fields:
	foreignKey = ""
	if field.referencedObject:
		foreignKey = """ALTER TABLE %(tableName)s ADD CONSTRAINT FK_%(tableName)s_%(tableColumn)s_%(foreignTable)s_%(foreignColumn)s FOREIGN KEY (`%(tableColumn)s`) REFERENCES %(foreignTable)s (`%(foreignColumn)s`);
""" % {	'tableName': (DATABASE + "_" + self.dbTableName if DATABASE != "" else self.dbTableName),
	'foreignTable': (DATABASE + "_" + field.referencedObject.dbTableName if DATABASE != "" else field.referencedObject.dbTableName),
	'foreignColumn': field.referencedObject.keyFields[0].dbName,
	'tableColumn': field.dbName 
	}
	foreignKeys += foreignKey
RETURN = foreignKeys
%%

