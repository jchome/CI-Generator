%[kind : views]
%[file : editMultiselect%%(self.obName)%%Fragment_view.php]
%[path : Views/Generated/%%(self.obName.title())%%]

%%
allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.autoincrement or not field.referencedObject :
		continue
	attributeCode = """
<?php /** Paste this code in the view of "edit*_view.php" : 

$data["multiselectOf"] = '%(referencedObjectName)s';
$this->load->view('%(obName_lower)s/editMultiselect%(obName)sFragment_view.php', $data);

*/
/** Paste this JS script in the "edit*.js"

$(function(){
	$("#%(structureObName)s_%(referencedID)s").multiselect().multiselectfilter(); 
});

*/ 

if($multiselectOf == '%(referencedObjectName)s' ){ ?>
	""" % { 'referencedObjectName' : field.referencedObject.obName,
		'obName' : self.obName,
		'obName_lower' : self.obName.lower(),
		'structureObName' : self.obName,
		'referencedID' : field.referencedObject.keyFields[0].dbName
	}

	attributeCode += """
<div class="control-group">
<label class="control-label" for="%(structureObName)s_%(referencedID)s"><?= lang('generated.%(referencedObject_lower)s.form.list.title')?> :</label>
<div class="controls"><select name="%(structureObName)s_%(referencedID)s[]" id="%(structureObName)s_%(referencedID)s" multiple="multiple">
<?php foreach ($%(referencedObject_lower)sCollection as $%(referencedObject_lower)s){
	$selection = false;
	foreach ($%(structureObName_lower)sCollection as $lien){
		if($%(referencedObject_lower)s->%(referencedID)s == $lien->%(dbName)s){
			$selection = True;
			break;
		}
	}
	if($selection){
		echo '<option value="'.$%(referencedObject_lower)s->%(referencedID)s.'" selected="selected">' . $%(referencedObject_lower)s->%(display)s . '</option>';
	}else{
		echo '<option value="'.$%(referencedObject_lower)s->%(referencedID)s.'">' . $%(referencedObject_lower)s->%(display)s . '</option>';
	}
}?>
</select>
<p class="help-block valtype"><?= lang('generated.%(structureObName_lower)s.form.%(dbName)s.description')?></p>
</div></div>

""" % { 'structureObName' : self.obName,
		'structureObName_lower' : self.obName.lower(),
		'dbName' : field.dbName,
		'referencedID' : field.referencedObject.keyFields[0].dbName,
		'referencedObject_lower' : field.referencedObject.obName.lower(),
		'display' : field.display
		}

	attributeCode += """
<?php } ?>


"""
	
	allAttributesCode += attributeCode
if not self.isCrossTable:
	RETURN = ""
else:
	RETURN = allAttributesCode
%%
