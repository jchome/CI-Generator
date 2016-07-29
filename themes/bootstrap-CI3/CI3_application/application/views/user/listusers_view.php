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
<head><!-- Liste des Users -->
<?php echo htmlHeader($this->lang->line('user.form.list.title')); ?>

</head>
<body>

	<?= htmlNavigation("user","list", $this->session); ?>
	
	<div class="container">

		<h2><?= $this->lang->line('user.form.list.title') ?></h2>
			<?php
				$msg = $this->session->flashdata('msg_info');    if($msg != ""){echo formatInfo($msg);} 
				$msg = $this->session->flashdata('msg_confirm'); if($msg != ""){echo formatConfirm($msg);}
				$msg = $this->session->flashdata('msg_warn');    if($msg != ""){echo formatWarn($msg);}
				$msg = $this->session->flashdata('msg_error');   if($msg != ""){echo formatError($msg);}
			?>
		
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
		<!-- table header auto-generated : -->
					<th class="sortable"><!-- usrlbnom -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrlbnom/<?= ($orderBy == 'usrlbnom'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrlbnom'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrlbnom'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrlbnom.label') ?></a></th>
					<th class="sortable"><!-- usrlbprn -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrlbprn/<?= ($orderBy == 'usrlbprn'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrlbprn'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrlbprn'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrlbprn.label') ?></a></th>
					<th class="sortable"><!-- usrlblgn -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrlblgn/<?= ($orderBy == 'usrlblgn'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrlblgn'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrlblgn'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrlblgn.label') ?></a></th>
					<th class="sortable"><!-- usrlbpwd -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrlbpwd/<?= ($orderBy == 'usrlbpwd'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrlbpwd'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrlbpwd'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrlbpwd.label') ?></a></th>
					<th class="sortable"><!-- usrlbmai -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrlbmai/<?= ($orderBy == 'usrlbmai'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrlbmai'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrlbmai'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrlbmai.label') ?></a></th>
					<th class="sortable"><!-- usrfipho -->
						<a href="<?=base_url()?>index.php/user/listusers/index/usrfipho/<?= ($orderBy == 'usrfipho'&& $asc == 'asc')?('desc'):('asc') ?>"
						<?php if($orderBy == 'usrfipho'&& $asc == 'asc') {?>
							class=" sortAsc"
						<?php }else if($orderBy == 'usrfipho'&& $asc == 'desc') {?>
							class=" sortDesc"
						<?php }?>
						><?= $this->lang->line('user.form.usrfipho.label') ?></a></th>
					<th><?= $this->lang->line('object.tableheader.actions') ?></th>
				</tr>
			</thead>
			<tbody>
<?php
$even = false;
$enum_usrfipho = array();
foreach($users as $user):

?>
	<tr>

				<td valign="top"><?=$user->usrlbnom?></td>
				<td valign="top"><?=$user->usrlbprn?></td>
				<td valign="top"><?=$user->usrlblgn?></td>
				<td valign="top"><input type="hidden" name="usrlbpwd" id="usrlbpwd" value="<?=$user->usrlbpwd?>">
			<span title="<?=$user->usrlbpwd?>">&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;&#9733;</span>
			</td>
				<td valign="top"><?=$user->usrlbmai?></td>
				<td valign="top"><a href="<?=base_url()?>www/uploads/<?=$user->usrfipho?>" target="_new" class="downloadFile">
				<?=$user->usrfipho?></a></td>
					<td><a class="btn btn-default" href="<?=base_url()?>index.php/user/edituser/index/<?=$user->usridusr?>" title="<?= $this->lang->line('form.button.edit') ?>"><i class="glyphicon glyphicon-edit"> </i></a>
						<a class="btn btn-danger" href="#" onclick="if( confirm('<?= $this->lang->line('user.message.askConfirm.deletion')?>')){document.location.href='<?=base_url()?>index.php/user/listusers/delete/<?=$user->usridusr?>';}" 
						title="<?= $this->lang->line('form.button.delete') ?>"
						><i class="glyphicon glyphicon-remove"> </i></a></td>
				</tr>
<?php 
$even = !$even; 
endforeach; ?>

			</tbody>
		</table>
	
		<div class="pagination">
			<ul>
			<?php if(isset($pagination)){ echo $pagination->create_links(); } ?>
			</ul>
		</div><!-- .pagination -->
		
		<a href="<?=base_url()?>index.php/user/createuser/index" class="btn btn-primary"><?= $this->lang->line('user.form.create.title') ?></a>
	</div><!-- .container -->
	
<?php echo bodyFooter(); ?>

<script src="<?= base_url() ?>www/js/views/user/listusers.js"></script>


</body>
</html>