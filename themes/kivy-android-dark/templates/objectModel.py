%[kind : models]
%[file : %%(self.obName.lower())%%Model.py]
%[path : models]
#!/usr/bin/python
# -*- coding: utf-8 -*-
'''%%(self.description)%%
'''
class %%(self.obName)%%():
	%%
RETURN = self.dbVariablesList("""''' (descrVar)s ; type (typeVar)s'''
\t(instVar)s = None;
""", 'instVar',  'typeVar', 'descrVar', 1)
%%
	
	def readFromDict(self, aDict):
		%%RETURN = self.dbVariablesList("""self.(instVar)s = aDict["(instVar)s"]""", 'instVar', 'typeVar', 'descrVar', 2)
		%%
		return self
	
	@staticmethod
	def readAllFromDict(aDictOfDict):
		allObjects = {}
		for key, aDict in aDictOfDict.iteritems():
			allObjects[key] = %%(self.obName)%%().readFromDict(aDict)
		return allObjects
	