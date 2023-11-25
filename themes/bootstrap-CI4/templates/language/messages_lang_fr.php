%[kind : lang]
%[file : %%(self.obName.title())%%.php] 
%[path : Language/fr/generated]
<?php
/**
 * Message file for entity %%(self.obName)%%
 * 
 * usage : lang("%%(self.obName.title())%%.message.askConfirm.deletion")
 */

return [
	'message.askConfirm.deletion' => "Désirez-vous supprimer ce %%(self.displayName)%% ?",
	'message.confirm.deleted' => "%%(self.displayName)%% supprimé",
	'message.confirm.added' => "%%(self.displayName)%% créé avec succès",
	'message.confirm.modified' => "%%(self.displayName)%% mis à jour avec succès",
	'form.create.title' => "Ajouter un %%(self.displayName.lower())%%",
	'form.edit.title' => "Editer un %%(self.displayName.lower())%%",
	'form.list.title' => "Liste des %%(self.displayName.lower())%%s",
	'menu.item' => "%%(self.displayName)%%",
%%allAttributesCode = ""
for field in self.fields:
	attributeCode = """	'form.%(dbName)s.label' => "%(obName)s",
	'form.%(dbName)s.description' => "%(desc)s",
""" % {	'dbName': field.dbName, 
		'obName': field.obName,
		'objectObName':self.obName.lower(),
		'desc' : field.description
	}
	allAttributesCode += attributeCode
	
RETURN = allAttributesCode
%%
];
