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
	
	def refreshData(self):
		self.purgeTable()
		allObjects = %%(self.obName)%%JsonRetriever().retrieveAll()
		for anObject in allObjects.itervalues():
			json_data = { %%
includesKey = True
RETURN = self.dbVariablesList('"(var)s" : anObject.(var)s', 'var', '', '', 0, includesKey)
%% }
			self.insertData(json_data)
		
	def saveOrUpdate(self, anObject):
		json_data = { %%
includesKey = True
RETURN = self.dbVariablesList('"(var)s" : anObject.(var)s', 'var', '', '', 0, includesKey)
%% }
		if anObject.%%(self.keyFields[0].dbName)%% is None:
			anObject.%%(self.keyFields[0].dbName)%% = self.insertData(json_data)
		else:
			self.updateData(json_data)

	
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
