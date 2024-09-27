%[kind : views]
%[file : create%%(self.obName.lower())%%.php]
%[path : Views/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

?>

	<div class="container-fluid">
	
		<h2><?= lang('generated/%%(self.obName.title())%%.form.create.title') ?></h2>
		
		<div class="row text-center ">
			<div class="col-12">
				<?= session()->getFlashdata('error') ?>
				<?= service('validation')->listErrors('errors_list') ?>
				<br />
			</div>
		</div>
		<div class="row-fluid">
<?php
echo form_open_multipart('Generated/%%(self.obName.lower())%%/create%%(self.obName.lower())%%/add', 'class="form-horizontal"');
?>

	<!-- list of variables - auto-generated : -->
	
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		## ne pas presenter les champs auto-increment
		attributeCode = "<!-- AUTO_INCREMENT : DO NOT DISPLAY THIS ATTRIBUTE - " + attributeCode + " -->"
		continue
	
	attributeCode += """
	<div class="row mb-3"><!-- %(obName)s : %(desc)s -->
		<label for="%(dbName)s" class="col-2 col-form-label">""" % { 'dbName' : field.dbName, 'obName' : field.obName,'desc' : field.description }

	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		## The Required attribute is not valid for FLAG field
		attributeCode += "* "

	attributeCode += """<?= lang('generated/%(objectObName)s.form.%(dbName)s.label') ?>
		</label>""" % { 'dbName' : field.dbName, 'objectObName' : self.obName.title() }

	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		<div class="col-3">"""
	else:
		attributeCode += """
		<div class="col-10">"""

	moreAttributes = ""
	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		moreAttributes = "required "
	
	if field.referencedObject and field.access == "default":
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" aria-describedby="%(dbName)sHelp" 
				class="form-control">""" % { 'dbName' : field.dbName }
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
		attributeCode += """
				<?php foreach ($%(referencedObject)sCollection as $%(referencedObject)sElt): ?>
				<option value="<?= $%(referencedObject)sElt['%(keyReference)s'] ?>" ><?= $%(referencedObject)sElt['%(display)s'] ?> </option>
				<?php endforeach;?>
			</select>""" % { 'display' : field.display, 
				'keyReference' : field.referencedObject.keyFields[0].dbName, 
				'referencedObject' : field.referencedObject.obName.lower(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName }
				
	elif field.referencedObject and field.access == "ajax" :
		attributeCode += """
			<input type="text" name="%(dbName)s_text" id="%(dbName)s_text" 
				aria-describedby="%(dbName)sHelp" autocomplete="off" class="form-control" %(moreAttributes)s/>
			<input type="hidden" name="%(dbName)s" id="%(dbName)s">""" % { 'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
		 	}
	elif field.sqlType.upper()[0:4] == "DATE":
		dateFormat = field.sqlType[5:-1]
		attributeCode += """
			<div class="input-group input-append date" data-date-format="%(dateFormat)s" id="datepicker_%(dbName)s">
				<input type="text" name="%(dbName)s" id="%(dbName)s" class="form-control" size="8" 
					aria-describedby="%(dbName)sHelp" maxlength="10" %(moreAttributes)s> 
				<span class="add-on"></span>
				<span class="input-group-text" id="basic-addon2"><i class="bi bi-calendar-event"></i></span>
			</div>""" % { 'dbName' : field.dbName, 
			'dateFormat' : dateFormat,
			'moreAttributes' : moreAttributes
		}
		
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """
			<div class="input-group">
				<span class="input-group-addon glyphicon glyphicon-lock"></span>
				<input type="password" placeholder="Password" name="%(dbName)s" id="%(dbName)s" 
					aria-describedby="%(dbName)sHelp" class="form-control" %(moreAttributes)s>
			</div>""" % { 'dbName' : field.dbName,
				'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "TEXT":
		attributeCode += """
			<input id="%(dbName)s" type="hidden" name="%(dbName)s" %(moreAttributes)s>
			<trix-editor input="%(dbName)s"></trix-editor>""" % { 'dbName' : field.dbName ,
				'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """
			<input class="input-file" id="%(dbName)s_file" name="%(dbName)s_file" class="form-control" type="file" %(moreAttributes)s/>
			<input type="hidden" name="%(dbName)s" id="%(dbName)s"/>""" % { 'dbName' : field.dbName, 
				'structureObName': self.obName.lower(),
				'moreAttributes' : moreAttributes
			}

	elif field.sqlType.upper()[0:4] == "FLAG":
		label = field.sqlType[5:-1].strip('"').strip("'")
		attributeCode += """
			<label class="checkbox">
				<input name="%(dbName)s" id="%(dbName)s" value="O" type="checkbox" %(moreAttributes)s/> %(label)s
			</label>""" % { 'dbName' : field.dbName, 
			'label': label.strip(), 
			'structureObName' : self.obName.lower(),
			'moreAttributes' : moreAttributes
			}
		
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" class="form-control" 
				aria-describedby="%(dbName)sHelp" %(moreAttributes)s>""" % { 
				'dbName' : field.dbName,
				'moreAttributes' : moreAttributes
			}
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
			
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """
				<option value="%(value)s" >%(text)s</option>""" % {
				'value': valueAndText[0].strip(), 
				'text': valueAndText[1].strip(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName
				}
		attributeCode += """
			</select>"""

	else:
		# for string, int, ...
		attributeCode += """
			<input class="form-control" type="text" name="%(dbName)s" 
				aria-describedby="%(dbName)sHelp" id="%(dbName)s" %(moreAttributes)s """ %{
			'dbName' : field.dbName,
			'moreAttributes' : moreAttributes
			}
		if field.getAttribute("check") and field.getAttribute("check") != "" :
			attributeCode += """onblur="checkField(this,%(regexp)s)" """ % {'regexp' : field.getAttribute("check")}
			attributeCode += """>""" % {'dbName' : field.dbName}
		else:
			attributeCode += ">"
			
	attributeCode += """
			<span id="%(dbName)sHelp" class="form-text">
				<?= lang('generated/%(objectObName)s.form.%(dbName)s.description')?>
			</span>
		</div>
		""" % {'dbName' : field.dbName, 'objectObName' : self.obName.title() }

	## Add the "today" button
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		<div class="col-3">
			<button class="btn btn-primary btn-sm" onclick="return today(%(dbName)s)">Aujoud'hui</button>
		</div>""" % {'dbName' : field.dbName}

	attributeCode += """
	</div>"""


	# ajouter le nouvel attribut, avec indentation si ce n'est pas le premier
	if allAttributesCode != "":
		allAttributesCode += "\n" 
	allAttributesCode += attributeCode

RETURN =  allAttributesCode
%%

		<hr>
		<div class="row">
			<div class="d-flex justify-content-around">
				<button type="submit" class="btn btn-primary"><?= lang('App.form.button.save') ?></button>
				<a href="<?= base_url() ?>/index.php/Generated/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index" type="button" class="btn btn-secondary"><?= lang('App.form.button.cancel') ?></a>
			</div>
		</div>
			
		</form>

		</div> <!-- .row-fluid -->
	</div> <!-- .container -->

<script src="<?= base_url() ?>/js/Generated/%%(self.obName.lower())%%/create%%(self.obName.lower())%%.js"></script>
