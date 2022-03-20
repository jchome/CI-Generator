%[kind : views]
%[file : list%%(self.obName.lower())%%s.php] 
%[path : Views/%%(self.obName.title())%%]
<?php
/*
 * Created by generator
 *
 */

if(session()->get('user_name') == "") {
	redirect('welcome/index');
}

?>
	<div class="container">

		<h2><?= lang('%%(self.obName.title())%%.form.list.title') ?></h2>
			<?php 
			$msg = session()->getFlashdata('msg_info');    if($msg != ""){echo '<div class="alert alert-info" role="alert">' . $msg . '</div>';}
			$msg = session()->getFlashdata('msg_confirm'); if($msg != ""){echo '<div class="alert alert-success" role="alert">' . $msg . '</div>';}
			$msg = session()->getFlashdata('msg_warn');    if($msg != ""){echo '<div class="alert alert-warning" role="alert">' . $msg . '</div>';}
			$msg = session()->getFlashdata('msg_error');   if($msg != ""){echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';}
			
		?>
		
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
		<!-- table header auto-generated : -->
					%%
RETURN = self.dbAndObVariablesList("""<th class=\"sortable\"><!-- (dbVar)s -->
						<a href="/index.php/%(obName_lower)s/list%(obName_lower)ss/index/(dbVar)s/<?= ($orderBy == '(dbVar)s'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == '(dbVar)s'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == '(dbVar)s'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= lang('%(obName)s.form.(dbVar)s.label') ?></a></th>""" % {'obName':self.obName.title(), 'obName_lower':self.obName.lower(), }, 'dbVar', 'obVar', 5, False)
%%
					<th><?= lang('App.object.tableheader.actions') ?></th>
				</tr>
			</thead>
			<tbody>
<?php
%%allAttributes = "" 
for field in self.fields:
	if field.dbName != self.keyFields[0].dbName:
		if field.sqlType.upper()[0:4] == "ENUM":
			enumTypes = field.sqlType[5:-1]
			for enum in enumTypes.split(','):
				valueAndText = enum.replace('"','').replace("'","").split(':')
				attributeCode = "\"%(value)s\"=>\"%(text)s\"" % {'value': valueAndText[0].strip(), 
					'text': valueAndText[1].strip()}
				if allAttributes != "":
					allAttributes += ", " + attributeCode
				else:
					allAttributes = attributeCode
			 
allEnums = "$enum_%(dbName)s = array(%(allAttributes)s);" % {'dbName' : field.dbName, 'allAttributes' : allAttributes }
RETURN = allEnums
%%
foreach($%%(self.obName.lower())%%s as $%%(self.obName.lower())%%):
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.referencedObject and field.access == "ajax" :
		attributeCode = """
	$%(dbName)s_text = ($%(structureObName)s->%(dbName)s == 0)?(new %(referencedObject)sModel()):($this->%(referencedObjectLower)sservice->getUnique($this->db, $%(structureObName)s->%(dbName)s));
""" % {
		'structureObName' : self.obName.lower(),
		'referencedObject': field.referencedObject.obName,
		'referencedObjectLower': field.referencedObject.obName.lower(),
		'dbName' : field.dbName
		}
	allAttributesCode += attributeCode
RETURN = allAttributesCode
%%
?>
	<tr>
%%allAttributesCode = ""

for field in self.fields:
	if field.dbName == self.keyFields[0].dbName:
		attributeCode = """<!-- $%(structureObName)s->%(dbName)s = <?= $%(structureObName)s->%(dbName)s ?> -->""" % {
					'structureObName' : self.obName.lower(),
					'dbName' : field.dbName}
	
	else:
		attributeCode = """
				<td valign="top">"""
		if field.referencedObject and field.access == "default":
			# si pas de lien, le champ vaut 0 (et la sequence commence à 1)
			attributeCode += """<?=($%(structureObName)s['%(dbName)s'] == 0)?(""):($%(referencedObject)sCollection[$%(structureObName)s->%(dbName)s]->%(display)s)?>
			""" % { 'display' : field.display, 
					'referencedObject' : field.referencedObject.obName.lower(),
					'structureObName' : self.obName.lower(),
					'dbName' : field.dbName}
		elif field.referencedObject and field.access == 'ajax':
			attributeCode += """<?=$%(dbName)s_text['%(display)s']?>""" % {
					'display' : field.display, 
					'dbName' : field.dbName }
		elif field.sqlType.upper()[0:4] == "FLAG":
			label = field.sqlType[5:-1].replace('"','').replace("'","")
			attributeCode += """<?= ($%(structureObName)s['%(dbName)s'] == "O")?("%(label)s"):("")?>""" % {'label' : field.obName,
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:4] == "ENUM":
			attributeCode += """<?=($%(structureObName)s['%(dbName)s'] == "")?(""):($enum_%(dbName)s[$%(structureObName)s->%(dbName)s])?>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:4] == "FILE":
			attributeCode += """<a href="/www/uploads/<?=$%(structureObName)s['%(dbName)s']?>" target="_new" class="downloadFile">
				<?=$%(structureObName)s['%(dbName)s']?></a>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:8] == "PASSWORD":
			attributeCode += """<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="<?=$%(structureObName)s['%(dbName)s']?>">
			<span title="<?=$%(structureObName)s['%(dbName)s']?>">&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;</span>
			""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		else:
			attributeCode += """<?=$%(structureObName)s['%(dbName)s']?>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
			 
		allAttributesCode += attributeCode + "</td>"
	
RETURN = allAttributesCode
%%
					<td>
						<a class="btn btn-default" 
							href="/index.php/%%(self.obName.lower())%%/edit%%(self.obName.lower())%%/index/<?=$%%(self.obName.lower())%%['%%(self.keyFields[0].dbName)%%']?>" 
							title="<?= lang('App.form.button.edit') ?>">
							<i class="glyphicon glyphicon-edit"> </i>
						</a>
						<a class="btn btn-danger" href="#" 
							onclick="if( confirm('<?= lang('%%(self.obName.title())%%.message.askConfirm.deletion')?>')){document.location.href='/index.php/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s/delete/<?=$%%(self.obName.lower())%%['%%(self.keyFields[0].dbName)%%']?>';}" 
							title="<?= lang('App.form.button.delete') ?>"
							><i class="glyphicon glyphicon-remove"> </i>
						</a>
					</td>
				</tr>
<?php 
endforeach; ?>

			</tbody>
		</table>
	
		<div class="pagination row">
			<ul class="pagination">
			<?php if(isset($pagination)){ echo $pagination->create_links(); } ?>
			</ul>
		</div><!-- .pagination -->
		
		<div class="row">
			<a href="<?= base_url('%%(self.obName.lower())%%/create%%(self.obName.lower())%%/index')?>"
			 	class="btn btn-primary"><?= lang('%%(self.obName.title())%%.form.create.title') ?></a>
		</div>
	</div><!-- .container -->
	

<script src="/www/js/views/%%(self.obName.lower())%%/list%%(self.obName.lower())%%s.js"></script>