<!doctype html>
<html>

<head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />

    <!-- Custom CSS - ->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/custom.css" / -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main-back.css" />

</head>

<body class="login">

<?php if (session()->getFlashdata('error')) : ?>
    <div class="row text-center">
        <div class="col-md-12 alert alert-danger" role="alert">
            <?= session()->getFlashdata('error') ?>
            <?= service('validation')->listErrors('errors_list') ?>
            <br />
        </div>
    </div>
<?php endif ?>

    <section>
        <div class="container">

            <div class="row justify-content-center">

                <div class="card p-0 col-10 col-md-8 col-lg-6 col-xl-4" style0="width: 26rem;">
                    <div class="card-header">
                        <h5><?= lang('App.message.welcome') ?></h5>
                    </div>

                    <div class="card-body">
                        <form action="<?= base_url() ?>/welcome/index" name="signin" id="signin" class="signin-form" method="POST">
                            <input type="hidden" name="formSend" value="true" />
                            <?= csrf_field() ?>
                            <div class="form-group mb-3">
                                <label class="label" for="login"><?= lang('App.message.type_your_id') ?></label>
                                <input type="text" class="form-control" placeholder="<?= lang('App.form.id') ?>" value="" id="login" name="login" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password"><?= lang('App.message.type_your_password') ?></label>
                                <input type="password" class="form-control" placeholder="<?= lang('App.form.password') ?>" name="password" id="password" required>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-primary rounded submit px-3"><?= lang('App.form.connect') ?></button>
                            </div>
                            <div class="d-flex justify-content-end forgotpassword">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal"><?= lang('App.form.forgotPassword') ?></a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?= base_url() ?>/welcome/resetPassword" name="lostPassword" id="lostPassword" class="signin-form" method="POST" onsubmit="return checkValidity();">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel"><?= lang('App.form.forgotPassword') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang('App.form.button.close') ?>"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="formSend" value="true" />
                <?= csrf_field() ?>

                <div class="form-group mb-3">
                    <label class="label" for="email"><?= lang('App.message.type_your_email') ?></label>
                    <input type="text" class="form-control" placeholder="<?= lang('App.form.email') ?>" name="email" id="email" required>
                    <div class="col-md-12">
                        <span id="error-email" style="color: red; display: none;"><?= lang('App.message.invalid_email') ?></span>
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.form.button.close') ?></button>
                <button type="submit" class="btn btn-primary"> <?= lang('App.form.button.next') ?> </button>
            </div>
        </form>
    </div>
  </div>
</div>


<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script language="javascript">
$('#lostPassword').submit(function(e) {
    $("#error-email").hide();
    var email = $("#email").val();
    if(email == ""){
        return false;
    }
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(emailReg.test( email )){
        // avoid to execute the actual submit of the form.
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');


        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                // Purge email
                $("#email").val("");
                
                alert(data.text); // show response from the php script.
            }
        });
    }else
    {
        $("#error-email").show();
    }
    return false;
});
</script>
</body>

</html>