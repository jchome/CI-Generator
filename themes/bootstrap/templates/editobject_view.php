%[kind : views]
%[file : edit%%(self.obName.lower())%%_view.php]
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php echo htmlHeader( $this->lang->line('%%(self.obName.lower())%%.form.edit.title') ); ?>

</head>
<body>

	<?= htmlNavigation("%%(self.obName.lower())%%","edit", $this->session); ?>
	
	<div class="container">
	
		<h2><?= $this->lang->line('%%(self.obName.lower())%%.form.edit.title') ?></h2>
			<?php
				$msg = $this->session->flashdata('msg_info');    if($msg != ""){echo formatInfo($msg);} 
				$msg = $this->session->flashdata('msg_confirm'); if($msg != ""){echo formatConfirm($msg);}
				$msg = $this->session->flashdata('msg_warn');    if($msg != ""){echo formatWarn($msg);}
				$msg = $this->session->flashdata('msg_error');   if($msg != ""){echo formatError($msg);}
			?>
			
		<div class="row-fluid">
<?php
$attributes_info = array('name' => 'EditForm', 'class' => 'form-horizontal');
$fields_info = array('%%(self.keyFields[0].dbName)%%' => $%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%);
echo form_open_multipart('%%(self.obName.lower())%%/edit%%(self.obName.lower())%%/save', $attributes_info, $fields_info );
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "ajax" :
		attributeCode += """
$%(dbName)s_%(referencedObject)s = ($%(structureObName)s->%(dbName)s == 0)?(new %(referencedObject)s_model()):(%(referencedObject)s_model::get%(referencedObject)s($this->db, $%(structureObName)s->%(dbName)s));""" % { 
			'structureObName': self.obName.lower(), 
			'dbName' : field.dbName, 
			'referencedObject' : field.referencedObject.obName
		}
	allAttributesCode += attributeCode
			
RETURN =  allAttributesCode
%%
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
	
	valueCode = "<?= $%(structureObName)s->%(dbName)s ?>" % { 'structureObName': self.obName.lower(), 'dbName' : field.dbName }
	
	attributeCode += """<div class="control-group"><!-- %(desc)s -->
	<label class="control-label" for="%(dbName)s">""" % { 'dbName' : field.dbName, 'desc' : field.description }

	if not field.nullable:
		attributeCode += "* "

	attributeCode += """<?= $this->lang->line('%(objectObName)s.form.%(dbName)s.label') ?> :</label>
	<div class="controls">
		""" % { 'dbName' : field.dbName, 'objectObName' : self.obName.lower() }

	cssClass = "inp-form"
	
	moreAttributes = ""
	if not field.nullable:
		moreAttributes = "required "
			
	if field.referencedObject and field.access == "default" :
		attributeCode += """<select name="%(dbName)s" id="%(dbName)s">
		""" % { 'dbName' : field.dbName }
		if field.nullable:
			attributeCode += """	<option value=""></option>
		"""
		attributeCode += """	<?php foreach ($%(referencedObject)sCollection as $%(referencedObject)sElt): ?>
				<option value="<?= $%(referencedObject)sElt->%(keyReference)s ?>" <?= ($%(referencedObject)sElt->%(keyReference)s == $%(structureObName)s->%(dbName)s)?("selected"):("")?>><?= $%(referencedObject)sElt->%(display)s ?> </option>
			<?php endforeach;?>
		</select>
		""" % { 'display' : field.display, 
				'keyReference' : field.referencedObject.keyFields[0].dbName, 
				'referencedObject' : field.referencedObject.obName.lower(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
				
	elif field.referencedObject and field.access == "ajax" :
		attributeCode += """<input type="text" name="%(dbName)s_text" id="%(dbName)s_text" value="<?= $%(dbName)s_%(referencedObject)s->%(display)s ?>" autocomplete="off" %(moreAttributes)s/>
		<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="<?= $%(structureObName)s->%(dbName)s ?>">
		""" % { 'dbName' : field.dbName,
				'referencedObject' : field.referencedObject.obName, 
				'structureObName' : self.obName.lower(),
				'display' : field.display,
				'moreAttributes' : moreAttributes
			 }
	elif field.sqlType.upper()[0:4] == "DATE":
		dateFormat = field.sqlType[5:-1]
		attributeCode += """<div data-date-format="%(dateFormat)s" id="datepicker_%(dbName)s"
			class="input-append date"><input type="text" name="%(dbName)s" id="%(dbName)s" size="8" maxlength="10" value="%(valueCode)s" %(moreAttributes)s> 
			<span class="add-on"><i class="icon-calendar"></i></span>
		</div>""" % { 'dbName' : field.dbName, 
			'valueCode' : valueCode, 
			'dateFormat' : dateFormat,
			'moreAttributes' : moreAttributes
		}
		
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """<div class="input-prepend">
								<span class="add-on"><i class="icon-key"></i></span> <input
									type="password" placeholder="Password" name="%(dbName)s" id="%(dbName)s" value="%(valueCode)s" %(moreAttributes)s>
							</div>""" % { 'dbName' : field.dbName, 
			'valueCode' : valueCode,
			'moreAttributes' : moreAttributes}
		
	elif field.sqlType.upper()[0:4] == "TEXT":
		attributeCode += """<textarea class="ckeditor" name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s>%(valueCode)s</textarea>""" % { 
			'dbName' : field.dbName, 
			'valueCode' : valueCode,
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """<input class="input-file" id="%(dbName)s_file" name="%(dbName)s_file" type="file" %(moreAttributes)s>
		<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="%(valueCode)s">
		<?php if($%(structureObName)s->%(dbName)s != "") { ?>
			<div class="span4" id="%(dbName)s_currentFile"><a href="<?=base_url()?>www/uploads/%(valueCode)s" target="_new"><i class="icon-file"></i> <?= $this->lang->line('form.button.download')?></a></div>
			<div class="span2" id="%(dbName)s_deleteButton"><a href="#" onclick='deleteFile_%(dbName)s()' class="btn"><i class="icon-trash"></i> <?= $this->lang->line('form.button.delete')?></a></div>
			<?php } ?>
		""" % { 'dbName' : field.dbName, 
				'valueCode' : valueCode,
				'structureObName': self.obName.lower(),
				'moreAttributes' : moreAttributes
			}

	elif field.sqlType.upper()[0:4] == "FLAG":
		label = field.sqlType[5:-1].strip('"').strip("'")
		attributeCode += """<label class="checkbox"> <input name="%(dbName)s" id="%(dbName)s" value="O" type="checkbox"
		<?= ($%(structureObName)s->%(dbName)s == "O")?("checked"):("")?>> %(label)s
							</label>""" % { 'dbName' : field.dbName, 
				'label': label.strip(), 
				'structureObName' : self.obName.lower() }
		
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode += """<select name="%(dbName)s" id="%(dbName)s" %(moreAttributes)s>
		""" % { 
			'dbName' : field.dbName,
			'moreAttributes' : moreAttributes }
		
		if field.nullable:
			attributeCode += """	<option value=""></option>
		"""
			
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """	<option value="%(value)s" <?= ($%(structureObName)s->%(dbName)s == "%(value)s")?("selected"):("")?> >%(text)s</option>
		""" % {'value': valueAndText[0].strip(), 
				'text': valueAndText[1].strip(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName }
		attributeCode += """</select>"""

	else:
		# for string, int, ...
		attributeCode += """<input class="input-xlarge valtype" type="text" name="%(dbName)s" id="%(dbName)s" value="%(valueCode)s" %(moreAttributes)s """ % { 
				'dbName' : field.dbName, 
				'valueCode' : valueCode,
				'moreAttributes' : moreAttributes
				}
		if field.getAttribute("check") and field.getAttribute("check") != "" :
			attributeCode += """onblur="checkField(this,%(regexp)s)" """ % {'regexp' : field.getAttribute("check")}
			attributeCode += """>""" % {'dbName' : field.dbName}
		else:
			attributeCode += ">"
			
	attributeCode += """
		<p class="help-block valtype"><?= $this->lang->line('%(objectObName)s.form.%(dbName)s.description')?></p>
	</div></div>
	""" % {'dbName' : field.dbName, 'objectObName' : self.obName.lower() }
	

	# ajouter le nouvel attribut, avec indentation si ce n'est pas le premier
	if allAttributesCode != "":
		allAttributesCode += "\n\t" 
	allAttributesCode += attributeCode

RETURN =  allAttributesCode
%%
			
			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><?= $this->lang->line('form.button.save') ?></button>
				<a href="<?=base_url()?>index.php/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index" type="button" class="btn"><?= $this->lang->line('form.button.cancel') ?></a>
			</div>
			
			
			</fieldset>

<?php
echo form_close('');
?>

		</div> <!-- .row-fluid -->
	</div> <!-- .container -->

<?php echo bodyFooter(); ?>

<script src="<?= base_url() ?>www/js/views/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%.js"></script>


</body>
</html>