%[kind : js]
%[file : create%%(self.obName.lower())%%.fancy.js]
%[path : ../www/js/views/%%(self.obName.lower())%%]
/* Javascript for create%%(self.obName.lower())%%_fancyview.php */

%%
jsCode = ""
hasDate = False
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		jsCode += """$('#datepicker_%(dbName)s').datepicker({ language: "fr-FR" });
""" % { 'dbName' : field.dbName }
RETURN = jsCode
%%
