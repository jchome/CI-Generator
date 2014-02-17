%[kind : subViews]
%[file : %%(self.obName.lower())%%s_all_view.php]
%[path : views/subviews]
<?php
?>

<!--  start table-content  -->
<div id="table-content">

	<!--  start product-table ..................................................................................... -->
	<form id="mainform" action="">
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
	<tr>
		<th class="table-header-check"><a id="toggle-all" ></a> </th>
		<!-- table header auto-generated : -->
			%%
RETURN = self.dbAndObVariablesList("""<th class=\"table-header-repeat line-left minwidth-1\">
						<a href="<?=base_url()?>index.php/list%ss/index/(dbVar)s/<?= ($orderBy == '(dbVar)s'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == '(dbVar)s'&& $asc == 'asc') {?>
							class="asc"
						<?php }else if($orderBy == '(dbVar)s'&& $asc == 'desc') {?>
							class="desc"
						<?php }?>
						>(obVar)s</a></th>""" % self.obName.lower(), 'dbVar', 'obVar', 3, False)
%%
		<th class="table-header-options line-left"><a href="#">Options</a></th>
	</tr>
<?php
$even = false;
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
?>
	<tr <?=($even)?('class="alternate-row"'):('')?>>
		<td><input type="checkbox"/></td>
%%allAttributesCode = ""

for field in self.fields:
	if field.dbName != self.keyFields[0].dbName:
		attributeCode = """
				<td valign="top">"""
		if field.referencedObject:
			# si pas de lien, le champ vaut 0 (et la sequence commence à 1)
			attributeCode += """<?=($%(structureObName)s->%(dbName)s == 0)?(""):($%(referencedObject)sCollection[$%(structureObName)s->%(dbName)s]->%(display)s)?>
			""" % { 'display' : field.display, 
					'referencedObject' : field.referencedObject.obName.lower(),
					'structureObName' : self.obName.lower(),
					'dbName' : field.dbName}
		elif field.sqlType.upper()[0:4] == "FLAG":
			label = field.sqlType[5:-1].replace('"','').replace("'","")
			attributeCode += """<?= ($%(structureObName)s->%(dbName)s == "O")?("%(label)s"):("")?>""" % {'label' : label,
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:4] == "ENUM":
			attributeCode += """<?=$enum_%(dbName)s[$%(structureObName)s->%(dbName)s]?>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:4] == "FILE":
			attributeCode += """<a href="<?=base_url()?>/www/uploads/<?=$%(structureObName)s->%(dbName)s?>" class="downloadFile">
				<?=$%(structureObName)s->%(dbName)s?></a>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		elif field.sqlType.upper()[0:8] == "PASSWORD":
			attributeCode += """<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="<?=$%(structureObName)s->%(dbName)s?>">
			<span title="<?=$%(structureObName)s->%(dbName)s?>">&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;</span>
			""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
		else:
			attributeCode += """<?=$%(structureObName)s->%(dbName)s?>""" % {
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
			 
		allAttributesCode += attributeCode + "</td>"
	
RETURN = allAttributesCode
			%%
		<td class="options-width">
		<a href="<?=base_url()?>index.php/edit%%(self.obName.lower())%%/index/<?=$%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%?>" title="Edit" class="icon-1 info-tooltip"></a>
		<a href="<?=base_url()?>index.php/list%%(self.obName.lower())%%s/delete/<?=$%%(self.obName.lower())%%->%%(self.keyFields[0].dbName)%%?>" title="Delete" class="icon-2 info-tooltip"></a>
		</td>
	</tr>
<?php 
$even = !$even; 
endforeach; ?>
	</table>
	<!--  end product-table................................... --> 
	</form>


	</div>
	<!--  end content-table  -->

	<!--  start actions-box ............................................... -- >
	<div id="actions-box">
		<a href="" class="action-slider"></a>
		<div id="actions-box-slider">
			<a href="" class="action-edit">Edit</a>
			<a href="" class="action-delete">Delete</a>
		</div>
		<div class="clear"></div>
	</div>
	<!-- end actions-box........... -->
	
	
	<!-- Pagination -->
	<table class="pagination">
		<tr>
			<?php if(isset($pagination)){ echo $pagination->create_links(); } ?>
		</tr>
	</table>
	<!-- /Pagination -->
	