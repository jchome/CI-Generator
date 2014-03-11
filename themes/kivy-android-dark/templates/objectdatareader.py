%[kind : helpers]
%[file : %%(self.obName.lower())%%datareader.py]
%[path : database]
#!/usr/bin/python
# -*- coding: utf-8 -*-

###
# AFTER CODE GENRATION : add this to the "__init__.py" file:
#  __all__ = ["datareader", ... , 
#		"%%(self.obName.lower())%%datareader.%%(self.obName)%%DataReader"
#	]
###

from datareader import DataReader, JsonRetriever

class %%(self.obName)%%DataReader(DataReader):
	
	def __init__(self):
		DataReader.__init__(self, "%%(self.dbTableName)%%", [%%
includesKey = True
RETURN = self.dbVariablesList('"(var)s"', 'var', '', '', 0, includesKey)
%%])


class %%(self.obName)%%JsonRetriever(JsonRetriever):
	
	def __init__(self):
		JsonRetriever.__init__(self)
		
	def retrieveAll(self):
		URL_ALL = "%%(self.obName.lower())%%/list%%(self.obName.lower())%%json"
		return self.retrieveFromUrl(URL_ALL)

%%allAttributeCode = ""
	# inclure les objets référencés
	
for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		continue
	elif field.referencedObject:
		attributeCode += """
	# get all <%(displayName)s> by <%(fieldObName)s>, using <%(fieldDbName)s>
	def retrieveAllBy_%(fieldDbName)s(self, value):
		URL_ALL = "%(objectNameLower)s/list%(objectNameLower)ssjson/findBy_%(fieldDbName)s/"+str(value)
		return self.retrieveFromUrl(URL_ALL)
		""" % {'fieldDbName' : field.dbName.lower(),
			'objectNameLower' : self.obName.lower(),
			'displayName' : self.displayName,
			'fieldObName' : field.obName
		}
		
	if attributeCode != "":
		allAttributeCode += attributeCode
	
RETURN = allAttributeCode
%%
