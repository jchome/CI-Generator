%[kind : js]
%[file : edit%%(self.obName.lower())%%.js]
%[path : ../public/js/Generated/%%(self.obName.lower())%%]
/* Javascript for edit%%(self.obName.lower())%%_view.php */

%%
jsCode = ""
hasDate = False
for field in self.fields:
	if field.sqlType.upper()[0:4] == "DATE":
		jsCode += """$('#datepicker_%(dbName)s').datepicker({ format:"dd/mm/yyyy", language: "fr" });
""" % { 'dbName' : field.dbName }
	elif field.sqlType.upper()[0:4] == "TEXT":
		jsCode += """ClassicEditor.create( document.querySelector('#%(dbName)s') );
""" % { 'dbName' : field.dbName }
RETURN = jsCode
%%


%%
allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.sqlType.upper()[0:4] == "FILE":
		attributeCode = """ 
function deleteFile_%(dbName)s(){
	if( confirm("Supprimer ce fichier ?") ){
		$("#%(dbName)s_deleteButton").hide();
		$("#%(dbName)s_currentFile").hide();
		$("#%(dbName)s").val("");
	}
}""" % {'dbName' : field.dbName
		}
		
		
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%

%%
allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "ajax" :
		attributeCode = """ 
$('#%(dbName)s_text').typeahead({
	source: function (query, process) {
		return $.getJSON(base_url()+'Generated/%(referencedObject)s/list%(referencedObject)ssjson/findLike_%(display)s/'+query,
		function (dataIN) {
			var result = dataIN.data.%(referencedObject)sCollection;
			data = new Array();
			for (i in result) {
				var group;
				group = {
					id: result[i].%(keyReference)s,
					name: result[i].%(display)s,
					toString: function () {
						return JSON.stringify(this);
					},
					toLowerCase: function () {
						return this.name.toLowerCase();
					},
					indexOf: function (string) {
						return String.prototype.indexOf.apply(this.name, arguments);
					},
					replace: function (string) {
						var value = '';
						value +=  this.name;
						if(typeof(this.level) != 'undefined') {
							value += ' <span class=\"pull-right muted\">';
							value += this.level;
							value += '</span>';
						}
						return String.prototype.replace.apply(value, arguments);
					}
				};

				data.push( group );
			}
			return process(data);
		});
	},
	updater: function (item) {
		var item = JSON.parse(item);
		$('#%(dbName)s').val(item.id);
		return item.name;
	}

});



""" % {
		'referencedObject': field.referencedObject.obName.lower(),
		'keyReference' : field.referencedObject.keyFields[0].dbName, 
		'dbName' : field.dbName,
		'display' : field.display
		}


	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%


%%
allAttributesCode = ""

for field in self.fields:
	if not field.nullable:
		fieldname = field.dbName
		if field.referencedObject and field.access == "ajax" :
			fieldname = field.dbName + "_text"
		elif field.sqlType.upper()[0:4] == "FILE":
			fieldname = field.dbName + "_file"
			
		attributeCode = """
//$("#%(fieldname)s").get(0).setCustomValidity('Champ requis');""" % { 'fieldname': fieldname }
		allAttributesCode += attributeCode
RETURN = allAttributesCode
%%
