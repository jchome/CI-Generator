<?php
$this->load->helper('url');
$this->load->helper('form');
$this->load->helper('template');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo htmlHeader($this->lang->line('forgotPassword.message.title')); ?>
</head>
<body>
	<div class="container">
		<div class="row text-center ">
			<div class="col-md-12">
				<br /> <br />
				<h2><?= $this->lang->line('forgotPassword.message.title') ?></h2>
<?php
if (isset ( $message ) && $message != "") {
?>
<div class="col-md-offset-4 col-md-4 col-sm-4">
	<div class="panel panel-danger">
		<div class="panel-heading">Erreur</div>
			<div class="panel-body">
				<?= $message ?></div>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
					<br />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
				<div class="panel panel-default">
					<div class="panel-body">
	
<?php
$attributes_info = array('name' => 'ForgotPasswordForm');
$fields_info = array("formSend" => "true");
echo form_open_multipart('login/forgotpassword/send', $attributes_info, $fields_info );
?>
						<div class="form-group">
							<label for="exampleInputEmail1"><?= $this->lang->line('forgotPassword.form.email') ?></label>
						</div>
						<div class="form-group input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-envelope"></i>
							</span>
							<input type="text" class="form-control" value="<?php echo set_value('email'); ?>" id="email" name="email" style="width: 100%">
							<?php echo form_error('email'); ?>
						</div>
						<input type="submit" class="btn btn-primary" value="<?= $this->lang->line('forgotPassword.form.send') ?>" name="Submit"/>
						<a href="<?=base_url()?>index.php/welcome" class="btn btn-default col-md-offset-5"><?= $this->lang->line('forgotPassword.form.cancel') ?></a>
					</div><!-- .panel-body -->

<?php
echo form_close('');
?>
				</div><!-- .panel -->
			</div>
		</div><!-- .row -->
	</div><!-- .container -->

<?php echo bodyFooter(); ?>

</body>
</html>