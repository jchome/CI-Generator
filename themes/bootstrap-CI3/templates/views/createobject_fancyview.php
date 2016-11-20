%[kind : views]
%[file : create%%(self.obName.lower())%%_fancyview.php]
%[path : views/%%(self.obName.lower())%%]
<?php
/*
 * Created by generator
 *
 */

$this->load->helper('form');
$this->load->helper('url');
$this->load->helper('template');
$this->load->helper('views');

if($this->session->userdata('user_name') == "") {
	redirect('welcome/index');
}
?>

	<?= htmlNavigation("%%(self.obName.lower())%%","fancy", $this->session); ?>
	
	<div class="container-fluid">
	
		<h2><?= $this->lang->line('%%(self.obName.lower())%%.form.create.title') ?></h2>
			
		<div class="row-fluid">
<?php
$attributes_info = array('name' => 'AddForm%%(self.obName)%%', 'id' => 'AddForm%%(self.obName)%%', 'class' => 'form-horizontal');
$fields_info = array();
echo form_open_multipart('%%(self.obName.lower())%%/create%%(self.obName.lower())%%json/add', $attributes_info, $fields_info );
?>

			<fieldset>
	<!-- list of variables - auto-generated : -->
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		## ne pas presenter les champs auto-increment
		attributeCode = "<!-- AUTO_INCREMENT : DO NOT DISPLAY THIS ATTRIBUTE - " + attributeCode + " -->"
		continue
	
	attributeCode += """
	<div class="control-group"> <!-- %(dbName)s : %(obName)s -->
		<label class="control-label" for="%(dbName)s">""" % { 'dbName' : field.dbName, 'obName': field.obName }

	if not field.nullable:
		attributeCode += "* "

	attributeCode += """
			<?= $this->lang->line('%(objectObName)s.form.%(dbName)s.label') ?> :
		</label>
		<div class="controls">""" % { 'dbName' : field.dbName, 'objectObName' : self.obName.lower() }

	cssClass = "inp-form"

	moreAttributes = ""
	if not field.nullable:
		moreAttributes = "required "
	
	if field.referencedObject and field.access == "default":
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s"> """ % { 'dbName' : field.dbName }
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
		attributeCode += """
				<?php foreach ($%(referencedObject)sCollection as $%(referencedObject)sElt): ?>
				<option value="<?= $%(referencedObject)sElt->%(keyReference)s ?>" ><?= $%(referencedObject)sElt->%(display)s ?> </option>
				<?php endforeach;?>
			</select>
		""" % { 'display' : field.display, 
				'keyReference' : field.referencedObject.keyFields[0].dbName, 
				'referencedObject' : field.referencedObject.obName.lower(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName }
				
	elif field.referencedObject and field.access == "ajax" :
		attributeCode += """<input type="text" name="%(dbName)s_text" id="%(dbName)s_text" autocomplete="off" %(moreAttributes)s/>
		<input type="hidden" name="%(dbName)s" id="%(dbName)s">
		""" % { 'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
		 	}
	elif field.sqlType.upper()[0:4] == "DATE":
		dateFormat = field.sqlType[5:-1]
		attributeCode += """<div data-date-format="%(dateFormat)s" id="datepicker_%(dbName)s"
			class="input-append date">
				<input type="text" name="%(dbName)s" id="%(dbName)s" size="8" maxlength="10" %(moreAttributes)s> 
				<span class="add-on"><i class="icon-calendar"></i></span>
		</div>""" % { 'dbName' : field.dbName,
			'dateFormat' : dateFormat,
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """<div class="input-prepend">
								<span class="add-on"><i class="icon-key"></i></span> <input
									type="password" placeholder="Password" name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s>
							</div>""" % { 'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "TEXT":
		attributeCode += """
			<textarea class="editor" name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s></textarea>
		""" % { 'dbName' : field.dbName ,
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """<input class="input-file" id="%(dbName)s_file" name="%(dbName)s_file" type="file" %(moreAttributes)s/>
		<input type="hidden" name="%(dbName)s" id="%(dbName)s"/>""" % { 'dbName' : field.dbName, 
			'structureObName': self.obName.lower(),
			'moreAttributes' : moreAttributes
			}

	elif field.sqlType.upper()[0:4] == "FLAG":
		label = field.sqlType[5:-1].strip('"').strip("'")
		attributeCode += """<label class="checkbox"> <input name="%(dbName)s" id="%(dbName)s" value="O" type="checkbox" %(moreAttributes)s/> %(label)s
							</label>""" % { 'dbName' : field.dbName, 
			'label': label.strip(), 
			'structureObName' : self.obName.lower(),
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode += """<select name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s>""" % { 'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
			}
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """<option value="%(value)s" >%(text)s</option>""" % {'value': valueAndText[0].strip(), 
				'text': valueAndText[1].strip(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName
				}
		attributeCode += """</select>"""

	else:
		# for string, int, ...
		attributeCode += """<input class="input-xlarge valtype form-control" type="text" name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s """ %{
			'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
			}
		if field.getAttribute("check") and field.getAttribute("check") != "" :
			attributeCode += """onblur="checkField(this,%(regexp)s)" """ % {'regexp' : field.getAttribute("check")}
			attributeCode += """>""" % {'dbName' : field.dbName}
		else:
			attributeCode += ">"
			
	attributeCode += """
			<span class="help-block valtype"><?= $this->lang->line('%(objectObName)s.form.%(dbName)s.description')?></span>
		</div>
	</div>""" % {'dbName' : field.dbName, 'objectObName' : self.obName.lower() }


	# ajouter le nouvel attribut, avec indentation si ce n'est pas le premier
	if allAttributesCode != "":
		allAttributesCode += "\n\t" 
	allAttributesCode += attributeCode

RETURN =  allAttributesCode
%%

		<hr>
		<div class="row">
			<div class="col-md-offset-2 col-md-2 col-xs-offset-2 col-xs-2">
			<button type="submit" class="btn btn-primary"><?= $this->lang->line('form.button.save') ?></button>
		</div>
		<div class="col-md-offset-4 col-md-2 col-xs-offset-4 col-xs-2">
			<a data-dismiss="modal" href="#" type="button" class="btn btn-default"><?= $this->lang->line('form.button.cancel') ?></a>
		</div>
			
		</fieldset>

<?php
echo form_close('');
?>

		</div> <!-- .row-fluid -->
	</div> <!-- .container -->

<script src="<?= base_url() ?>www/js/views/%%(self.obName.lower())%%/create%%(self.obName.lower())%%.fancy.js"></script>
