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

	<?= htmlNavigation("user","fancy", $this->session); ?>
	
	<div class="container">
	
		<h2><?= $this->lang->line('user.form.create.title') ?></h2>
			
		<div class="row-fluid">
<?php
$attributes_info = array('name' => 'AddFormUser', 'id' => 'AddFormUser', 'class' => 'form-horizontal');
$fields_info = array();
echo form_open_multipart('user/createuserjson/add', $attributes_info, $fields_info );
?>

			<fieldset>
	<!-- list of variables - auto-generated : -->

	<div class="form-group"> <!-- usrlbnom : Nom -->
		<label class="col-md-3 control-label" for="usrlbnom">* 
			<?= $this->lang->line('user.form.usrlbnom.label') ?> :
		</label>
		<div class="col-md-7"><input class="input-xlarge valtype form-control" type="text" name="usrlbnom" id="usrlbnom" required  >
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrlbnom.description')?></span>
		</div>
	</div>
	
	<div class="form-group"> <!-- usrlbprn : Prénom -->
		<label class="col-md-3 control-label" for="usrlbprn">
			<?= $this->lang->line('user.form.usrlbprn.label') ?> :
		</label>
		<div class="col-md-7"><input class="input-xlarge valtype form-control" type="text" name="usrlbprn" id="usrlbprn"  >
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrlbprn.description')?></span>
		</div>
	</div>
	
	<div class="form-group"> <!-- usrlblgn : Login -->
		<label class="col-md-3 control-label" for="usrlblgn">* 
			<?= $this->lang->line('user.form.usrlblgn.label') ?> :
		</label>
		<div class="col-md-7"><input class="input-xlarge valtype form-control" type="text" name="usrlblgn" id="usrlblgn" required  >
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrlblgn.description')?></span>
		</div>
	</div>
	
	<div class="form-group"> <!-- usrlbpwd : Password -->
		<label class="col-md-3 control-label" for="usrlbpwd">* 
			<?= $this->lang->line('user.form.usrlbpwd.label') ?> :
		</label>
		<div class="col-md-7"><div class="input-prepend">
								<span class="add-on"><i class="icon-key"></i></span> <input
									type="password" placeholder="Password" name="usrlbpwd" id="usrlbpwd" required >
							</div>
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrlbpwd.description')?></span>
		</div>
	</div>
	
	<div class="form-group"> <!-- usrlbmai : Email -->
		<label class="col-md-3 control-label" for="usrlbmai">* 
			<?= $this->lang->line('user.form.usrlbmai.label') ?> :
		</label>
		<div class="col-md-7"><input class="input-xlarge valtype form-control" type="text" name="usrlbmai" id="usrlbmai" required  >
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrlbmai.description')?></span>
		</div>
	</div>
	
	<div class="form-group"> <!-- usrfipho : Photo -->
		<label class="col-md-3 control-label" for="usrfipho">
			<?= $this->lang->line('user.form.usrfipho.label') ?> :
		</label>
		<div class="col-md-7"><input class="input-file" id="usrfipho_file" name="usrfipho_file" type="file" />
		<input type="hidden" name="usrfipho" id="usrfipho"/>
			<span class="help-block valtype"><?= $this->lang->line('user.form.usrfipho.description')?></span>
		</div>
	</div>

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

<script src="<?= base_url() ?>www/js/views/user/createuser.fancy.js"></script>
