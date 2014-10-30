%[kind : helpers]
%[file : %%(self.obName.lower())%%datareader.py]
%[path : database]
#!/usr/bin/python
# -*- coding: utf-8 -*-

### generated from template <objectdatareader.py>
###
# AFTER CODE GENRATION : add this to the "__init__.py" file:
#  __all__ = ["datareader", ... , 
#		"%%(self.obName.lower())%%datareader.%%(self.obName)%%DataReader", "%%(self.obName.lower())%%datareader.%%(self.obName)%%JsonRetriever"
#	]
###

from .datareader import DataReader, JsonRetriever
from models.%%(self.obName.lower())%%Model import %%(self.obName)%%

"""%%(self.description)%%
"""
class %%(self.obName)%%DataReader(DataReader):
	
	def __init__(self):
		DataReader.__init__(self, "%%(self.dbTableName)%%", "%%(self.keyFields[0].dbName)%%", [%%
includesKey = True
RETURN = self.dbVariablesList('"(var)s"', 'var', '', '', 0, includesKey)
%%])
		
	def getAllRecords(self):
		fullDict = DataReader.getAllRecords(self)
		return %%(self.obName)%%.readAllFromDict(fullDict)
	
	def refreshData(self, message_writer = None):
		self.purgeTable(message_writer)
		allObjects = %%(self.obName)%%JsonRetriever().retrieveAll()
		for anObject in allObjects.itervalues():
			json_data = { %%
includesKey = True
RETURN = self.dbVariablesList('"(var)s" : anObject.(var)s', 'var', '', '', 0, includesKey)
%% }
			self.insertData(json_data, message_writer)
%%allAttributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode = """
			if anObject.%(dbName)s is not None and anObject.%(dbName)s != "":
				message_writer.write("Recuperation de fichier (%(field_obName)s)")
				%(obName)sJsonRetriever().retrieve_file_%(dbName)s(anObject.%(keyField)s, self.stored_files_path)""" % { 
			'dbName' : field.dbName,
			'obName' : self.obName,
			'keyField' : self.keyFields[0].dbName,
			'field_obName': field.obName
			}
		allAttributeCode += attributeCode
RETURN = allAttributeCode
%%

	def saveOrUpdate(self, anObject, message_writer = None):
		json_data = { %%
includesKey = True
RETURN = self.dbVariablesList('"(var)s" : anObject.(var)s', 'var', '', '', 0, includesKey)
%% }
		if anObject.%%(self.keyFields[0].dbName)%% is None:
			anObject.%%(self.keyFields[0].dbName)%% = self.insertData(json_data, message_writer)
		else:
			self.updateData(json_data, message_writer)

	
%%allAttributeCode = ""
	# récupération par la clé et par les objets référencés
	
for field in self.fields:
	attributeCode = ""
	if field.referencedObject or field.isKey:
		attributeCode += """
	# get all <%(displayName)s> by <%(fieldObName)s>, using <%(fieldDbName)s>
	def getAllRecordsBy_%(fieldDbName)s(self, value):
		fullDict = DataReader.getAllRecordsEquals(self, "%(fieldDbName)s", value)
		return %(objectName)s.readAllFromDict(fullDict)
		""" % {'fieldDbName' : field.dbName.lower(),
			'displayName' : self.displayName,
			'fieldObName' : field.obName,
			'objectName' : self.obName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%


class %%(self.obName)%%JsonRetriever(JsonRetriever):
	
	def __init__(self):
		JsonRetriever.__init__(self)
		
	def retrieveAll(self):
		URL_ALL = "%%(self.obName.lower())%%/list%%(self.obName.lower())%%sjson"
		fullDict = self.retrieveFromUrl(URL_ALL)
		return %%(self.obName)%%.readAllFromDict(fullDict)

%%allAttributeCode = ""
	# récupération par la clé et par les objets référencés
	
for field in self.fields:
	attributeCode = ""
	if field.referencedObject or field.isKey:
		attributeCode += """
	# get all <%(displayName)s> by <%(fieldObName)s>, using <%(fieldDbName)s>
	def retrieveAllBy_%(fieldDbName)s(self, value):
		URL_ALL = "%(objectNameLower)s/list%(objectNameLower)ssjson/findBy_%(fieldDbName)s/"+str(value)
		fullDict = self.retrieveFromUrl(URL_ALL)
		return %(objectName)s.readAllFromDict(fullDict)
		""" % {'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower(),
			'displayName' : self.displayName,
			'fieldObName' : field.obName,
			'objectName' : self.obName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%

%%allAttributeCode = ""
for field in self.fields:
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode = """
	def retrieve_file_%(dbName)s(self, key, path_to_save):
		URL_GET_FILE = "%(objectNameLower)s/get%(objectNameLower)sjson/get_file_%(dbName)s/" + key
		self.retrieveFile(URL_GET_FILE, "%(dbName)s", path_to_save)
""" %	{ 'dbName' : field.dbName,
			'obName' : self.obName,
			'objectNameLower' : self.obName.lower()
			}
		allAttributeCode += attributeCode
RETURN = allAttributeCode
%%

	