%[kind : js]
%[file : create%%(self.obName.lower())%%.fancy.js]
%[path : ../public/js/views/%%(self.obName.lower())%%]
/* Javascript for create%%(self.obName.lower())%%_fancyview.php */

%%
jsCode = ""
hasDate = False
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		jsCode += """$('#datepicker_%(dbName)s').datepicker({ format:"dd/mm/yyyy", language: "fr" });
""" % { 'dbName' : field.dbName }
RETURN = jsCode
%%

/**
 * Envoi du form : intercepter l'envoi pour mettre à jour la liste et fermer la modale
 */
$('#AddForm%%(self.obName)%%').ajaxForm(function(data){
	// recuperation de l'objet sauvegardé en JSON
	// var objectSaved = JSON.parse(data);
	
	// fermeture de la modale
	$('#modal_create%%(self.obName.lower())%%').modal('hide');
});
