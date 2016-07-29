<?php
$this->load->helper ( 'url' );
$this->load->helper ( 'form' );
$this->load->helper ( 'template' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo htmlHeader("Welcome"); ?>
	
</head>
<body>

	<div class="container">
		<div class="row text-center ">
			<div class="col-md-12">
				<br /> <br />
				<h2>Connexion</h2>

<?php
if (isset ( $message ) && $message != "") {
?>
<div class="col-md-offset-4 col-sm-offset-4 col-md-4 col-sm-4">
	<div class="panel panel-danger">
		<div class="panel-heading">Attention</div>
		<div class="panel-body">
			<?= $message ?>
		</div>
	</div>
</div>
<?php
}
?>
					<br />
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong> Entrez vos identifiants </strong>
						</div>
						<div class="panel-body">
<?php
$attributes_info = array (
		'name' => 'LoginForm',
		'role' => 'form' 
);
$fields_info = array (
		"formSend" => "true" 
);
echo form_open_multipart ( 'welcome/index', $attributes_info, $fields_info );
?>
							<br />
							<div class="form-group input-group">
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-user"></i>
								</span> 
								<input type="text" class="form-control" placeholder="identifiant" value="<?php echo set_value('login'); ?>" id="login" name="login" />
							</div>
							<div class="form-group input-group">
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-lock"></i>
								</span>
								<input type="password" class="form-control" placeholder="mot de passe" name="password" id="password" />
							</div>

							<input type="submit" class="btn btn-primary btn-large" value="<?= $this->lang->line('connect') ?>" name="Submit"/>
							<div class="clear"></div>
							<div class="forgotpassword text-right">
								<a href="<?= base_url() ?>index.php/user/forgotpassword/index"><i class="glyphicon glyphicon-question-sign"></i> <?= $this->lang->line('forgotPassword.message.title') ?></a>
							</div>
						
<?php
echo form_close ( '' );
?>
						</div><!-- .panel-body -->

					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-4 -->
			</div><!-- .row -->


<?php echo bodyFooter(); ?>

</body>
</html>