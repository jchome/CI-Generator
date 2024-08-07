%[kind : views]
%[file : edit%%(self.obName.lower())%%.php]
%[path : Views/Generated/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

?>

	<div class="container-fluid">
	
		<h2><?= lang('generated/%%(self.obName.title())%%.form.edit.title') ?></h2>
		
		<div class="row text-center ">
			<div class="col-12">
				<?= session()->getFlashdata('error') ?>
				<?= service('validation')->listErrors('errors_list') ?>
				<br />
			</div>
		</div>
			
		<div class="row-fluid">
<?php
$attributes_info = array('name' => 'EditForm', 'class' => 'form-horizontal');
$fields_info = array('%%(self.keyFields[0].dbName)%%' => $%%(self.obName.lower())%%['%%(self.keyFields[0].dbName)%%']);
echo form_open_multipart('Generated/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%/save', $attributes_info, $fields_info );
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "ajax" :
		attributeCode = """
	$%(dbName)s_text = ($%(structureObName)s['%(dbName)s'] == 0)?(App\Models\%(referencedObject)sModel::$empty):((new \App\Models\%(referencedObject)sModel())->where('%(keyReference)s', $%(structureObName)s['%(dbName)s'])->first());
""" % {
		'structureObName' : self.obName.lower(),
		'referencedObject': field.referencedObject.obName.title(),
		'referencedObjectLower': field.referencedObject.obName.lower(),
		'keyReference' : field.referencedObject.keyFields[0].dbName, 
		'dbName' : field.dbName
		}
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%
?>

	<!-- list of variables - auto-generated : -->
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		## ne pas presenter les champs auto-increment
		attributeCode = "<!-- AUTO_INCREMENT : DO NOT DISPLAY THIS ATTRIBUTE - " + attributeCode + " -->"
		continue
	
	valueCode = "<?= $%(structureObName)s['%(dbName)s'] ?>" % { 'structureObName': self.obName.lower(), 'dbName' : field.dbName }
	
	attributeCode += """
	<div class="row mb-3"><!-- %(obName)s : %(desc)s -->
		<label for="%(dbName)s" class="col-2 col-form-label">""" % { 'dbName' : field.dbName, 'obName' : field.obName,'desc' : field.description }

	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		## The Required attribute is not valid for FLAG field
		attributeCode += "* "

	attributeCode += """<?= lang('generated/%(objectObName)s.form.%(dbName)s.label') ?>
		</label>
		""" % { 'dbName' : field.dbName, 'objectObName' : self.obName.title() }

	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		<div class="col-3">"""
	else:
		attributeCode += """
		<div class="col-10">"""
	
	moreAttributes = ""
	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		moreAttributes = "required "
			
	if field.referencedObject and field.access == "default" :
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" aria-describedby="%(dbName)sHelp" 
				class="form-control">""" % { 'dbName' : field.dbName }
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
		attributeCode += """
				<?php foreach ($%(referencedObject)sCollection as $%(referencedObject)sElt): ?>
				<option value="<?= $%(referencedObject)sElt['%(keyReference)s'] ?>" <?= ($%(referencedObject)sElt['%(keyReference)s'] == $%(structureObName)s['%(dbName)s'])?("selected"):("")?>><?= $%(referencedObject)sElt['%(display)s'] ?> </option>
				<?php endforeach;?>
			</select>""" % { 'display' : field.display, 
				'keyReference' : field.referencedObject.keyFields[0].dbName, 
				'referencedObject' : field.referencedObject.obName.lower(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
				
	elif field.referencedObject and field.access == "ajax" :
		attributeCode += """
			<input type="text" name="%(dbName)s_text" id="%(dbName)s_text" 
				aria-describedby="%(dbName)sHelp" class="form-control" 
				value="<?= $%(dbName)s_text['%(display)s'] ?>" autocomplete="off" %(moreAttributes)s/>
			<input type="hidden" name="%(dbName)s" id="%(dbName)s" 
				value="<?= $%(structureObName)s['%(dbName)s'] ?>">""" % { 'dbName' : field.dbName,
				'referencedObject' : field.referencedObject.obName, 
				'structureObName' : self.obName.lower(),
				'display' : field.display,
				'moreAttributes' : moreAttributes
			 }
		
	elif field.sqlType.upper()[0:4] == "DATE":
		dateFormat = field.sqlType[5:-1]
		attributeCode += """
			<div class="input-group input-append date" data-date-format="%(dateFormat)s" id="datepicker_%(dbName)s">
				<input type="text" name="%(dbName)s" id="%(dbName)s" class="form-control" size="8" 
					aria-describedby="%(dbName)sHelp" maxlength="10" value="%(valueCode)s" %(moreAttributes)s> 
				<span class="add-on"></span>
				<span class="input-group-text" id="basic-addon2"><i class="bi bi-calendar-event"></i></span>
			</div>""" % { 'dbName' : field.dbName, 
			'valueCode' : valueCode, 
			'dateFormat' : dateFormat,
			'moreAttributes' : moreAttributes
		}
		
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """
			<input type="password" id="%(dbName)s" name="%(dbName)s" 
					aria-describedby="%(dbName)sHelp" class="form-control" id="%(dbName)s" 
					value="%(valueCode)s" %(moreAttributes)s>""" % { 'dbName' : field.dbName, 
			'valueCode' : valueCode,
			'moreAttributes' : moreAttributes}
		
	elif field.sqlType.upper()[0:4] == "TEXT":
		attributeCode += """
			<input id="%(dbName)s" type="hidden" name="%(dbName)s" 
				value="<?= ($%(structureObName)s['%(dbName)s'] == null)?(""):(htmlentities($%(structureObName)s['%(dbName)s'])) ?>" %(moreAttributes)s>
			<trix-editor input="%(dbName)s"></trix-editor>""" % { 
			'dbName' : field.dbName, 
			'valueCode' : valueCode,
			'moreAttributes' : moreAttributes,
			'structureObName' : self.obName.lower()
			}
		
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """
			<?php if($%(structureObName)s['%(dbName)s'] != "") { ?>
			<div class="row">
				<img src="<?= base_url() ?>/uploads/<?= $%(structureObName)s['%(dbName)s'] ?>"class="col-4 img-fluid mb-4" style="width: 150px;">
			</div>
			<div class="row">
				<div class="col-2"><i><?= lang('App.form.file.current')?></i></div>
				<div class="col-2" id="%(dbName)s_currentFile">
					<a href="<?= base_url() ?>/uploads/%(valueCode)s" target="_new" class="btn btn-primary btn-sm">
						<i class="bi bi-file-earmark-fill"></i> <?= lang('App.form.button.download')?>
					</a>
				</div>
				<div class="col-2" id="%(dbName)s_deleteButton">
					<a onclick='deleteFile_%(dbName)s()' class="btn btn-danger btn-sm">
						<i class="bi bi-trash"></i> <?= lang('App.form.button.delete')?>
					</a>
				</div>
			</div>
			<hr/>
			<?php } ?>
			<div class="row">
				<div class="col-2"><i><?= lang('App.form.file.new')?></i></div>
				<div class="col-10">
					<input class="input-file" id="%(dbName)s_file" name="%(dbName)s_file" class="form-control" type="file">
					<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="%(valueCode)s">
				</div>
			</div>""" % { 'dbName' : field.dbName, 
				'valueCode' : valueCode,
				'structureObName': self.obName.lower(),
				'moreAttributes' : moreAttributes
			}

	elif field.sqlType.upper()[0:4] == "FLAG":
		label = field.sqlType[5:-1].strip('"').strip("'")
		attributeCode += """
			<label class="checkbox">
				<input name="%(dbName)s" id="%(dbName)s" value="O" type="checkbox"
					<?= ($%(structureObName)s['%(dbName)s'] == "O")?("checked"):("")?>> %(label)s
			</label>""" % { 'dbName' : field.dbName, 
				'label': label.strip(), 
				'structureObName' : self.obName.lower() }
		
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" class="form-control" 
				aria-describedby="%(dbName)sHelp" %(moreAttributes)s>""" % { 
			'dbName' : field.dbName,
			'moreAttributes' : moreAttributes }
		
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
			
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """
				<option value="%(value)s" <?= ($%(structureObName)s['%(dbName)s'] == "%(value)s")?("selected"):("")?> >%(text)s
				</option>""" % {'value': valueAndText[0].strip(), 
				'text': valueAndText[1].strip(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName }
		attributeCode += """
			</select>"""

	else:
		# for string, int, ...
		attributeCode += """
			<input class="form-control" type="text" name="%(dbName)s" 
			aria-describedby="%(dbName)sHelp" id="%(dbName)s" value="%(valueCode)s" %(moreAttributes)s """ % { 
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
			<span id="%(dbName)sHelp" class="form-text">
				<?= lang('generated/%(objectObName)s.form.%(dbName)s.description')?>
			</span>
		</div>""" % {'dbName' : field.dbName, 'objectObName' : self.obName.title() }
	
		
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
		allAttributesCode += "\n\t" 
	allAttributesCode += attributeCode

RETURN =  allAttributesCode
%%
		
		
		<hr>
		<div class="row">
			<div class="d-flex justify-content-around">
				<button type="submit" class="btn btn-primary"><?= lang('App.form.button.save') ?></button>
				<a href="<?= base_url() ?>/Generated/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/index" type="button" class="btn btn-secondary"><?= lang('App.form.button.cancel') ?></a>
			</div>
		</div>
			

<?php
echo form_close('');
?>

		</div> <!-- .row-fluid -->
	</div> <!-- .container -->

<script src="<?= base_url() ?>/js/Generated/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%.js"></script>
