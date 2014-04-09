%[kind : models]
%[file : %%(self.obName.lower())%%Model.py]
%[path : models]
#!/usr/bin/python
# -*- coding: utf-8 -*-
'''%%(self.description)%%
'''
class %%(self.obName)%%():
	
	def __init__(self):
		%%
RETURN = self.dbVariablesList("""''' (descrVar)s ; type (typeVar)s'''
\t\tself.(instVar)s = None;
""", 'instVar',  'typeVar', 'descrVar', 2)
%%
	
	def readFromDict(self, aDict):
		%%RETURN = self.dbVariablesList("""self.(instVar)s = aDict["(instVar)s"]""", 'instVar', 'typeVar', 'descrVar', 2)
		%%
		return self
	
	def toDict(self):
		return { %%
		RETURN = self.dbVariablesList('"(instVar)s": self.(instVar)s', 'instVar', 'typeVar', 'descrVar', 0)
		%%}
	
	@staticmethod
	def readAllFromDict(aDictOfDict):
		allObjects = {}
		for key, aDict in aDictOfDict.iteritems():
			allObjects[key] = %%(self.obName)%%().readFromDict(aDict)
		return allObjects
	
	def __repr__(self):
		return "<%%(self.obName)%% instance : %s>" % ( self.toDict() )
	
	