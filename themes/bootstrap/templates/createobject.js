%[kind : js]
%[file : create%%(self.obName.lower())%%.js]
%[path : ../www/js/views/%%(self.obName.lower())%%]
/* Javascript for create%%(self.obName.lower())%%_view.php */

%%
allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "ajax" :
		attributeCode = """ 
$('#%(dbName)s_text').typeahead({
	source: function (query, process) {
		return $.get(base_url()+'index.php/%(referencedObject)s/list%(referencedObject)ssjson/findBy_%(display)s/'+query,
		{ /*query: no more parameters*/ }, function (dataIN_str) {
			data = new Array();
			var dataIN = JSON.parse(dataIN_str);
			for (i in dataIN) {
				var group;
				group = {
					id: dataIN[i].%(keyReference)s,
					name: dataIN[i].%(display)s,
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
						return String.prototype.replace.apply('<div>' + value + '</div>', arguments);
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
