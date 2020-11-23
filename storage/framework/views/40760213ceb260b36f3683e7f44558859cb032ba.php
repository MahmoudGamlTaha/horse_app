<!doctype html>
<html lang="<?php echo e(app()->getLocale(), false); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title, false); ?></title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <style type="text/css">
            #msg{
                text-align: center;
            }
            .error{
                color: #ff0000;
                font-weight: normal;
            }
            .success{
                color: #418802;
            }
        </style>
</head>
<body>
<div class="container">
    <div id="signupbox" style=" margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1><?php echo e($title, false); ?></h1>
            </div>
            <div class="panel-body" >
                    <form  class="form-horizontal" id="formInstall">
                        <div id="div_database_host" class="form-group required">
                            <label for="database_host"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.database_host'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="database_host"  name="database_host" placeholder="<?php echo e(trans('language.install.database_host'), false); ?>" style="margin-bottom: 10px" type="text" value="127.0.0.1" />
                            </div>
                        </div>
                        <div id="div_database_port" class="form-group required">
                            <label for="database_port"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.database_port'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="database_port"  name="database_port" placeholder="<?php echo e(trans('language.install.database_port'), false); ?>" style="margin-bottom: 10px" type="number" value="3306" />
                            </div>
                        </div>
                        <div id="div_database_name" class="form-group required">
                            <label for="database_name"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.database_name'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="database_name"  name="database_name" placeholder="<?php echo e(trans('language.install.database_name'), false); ?>" style="margin-bottom: 10px" type="text" value="s-cart" />
                            </div>
                        </div>
                        <div id="div_database_user" class="form-group required">
                            <label for="database_user"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.database_user'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="database_user"  name="database_user" placeholder="<?php echo e(trans('language.install.database_user'), false); ?>" style="margin-bottom: 10px" type="text" value="root" />
                            </div>
                        </div>
                        <div id="div_database_password" class="form-group required">
                            <label for="database_password"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.database_password'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="database_password"  name="database_password" placeholder="<?php echo e(trans('language.install.database_password'), false); ?>" style="margin-bottom: 10px" type="password" value="" />
                            </div>
                        </div>
                        <div id="div_admin_url" class="form-group required">
                            <label for="admin_url"  required class="control-label col-md-4  requiredField"> <?php echo e(trans('language.install.admin_url'), false); ?><span class="asteriskField">*</span> </label>
                            <div class="controls col-md-8 ">
                                <input class="input-md  textinput textInput form-control" id="admin_url"  name="admin_url" placeholder="<?php echo e(trans('language.install.admin_url'), false); ?>" style="margin-bottom: 10px" type="text" value="system_admin" />
                            </div>
                        </div>


                        <div id="msg" class="form-group"></div>
                        <div class="form-group">
                            <div class="controls col-md-4 "></div>
                            <div class="controls col-md-8 ">
                                <input type="button" data-loading-text="<?php echo e(trans('language.install.installing_button'), false); ?>"  value="<?php echo e(trans('language.install.installing'), false); ?>" class="btn btn-primary btn btn-info" id="submit-install" />
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
$('#submit-install').click(function(event) {
    validateForm();
    if($("#formInstall").valid()){
        $(this).button('loading');
        $('#msg').removeClass('error');
        $('#msg').removeClass('success');
            $('#msg').html('<?php echo e(trans('language.install.env.process'), false); ?>');
            $.ajax({
                url: 'install.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    database_host:$('#database_host').val(),
                    database_port:$('#database_port').val(),
                    database_name:$('#database_name').val(),
                    database_user:$('#database_user').val(),
                    admin_url:$('#admin_url').val(),
                    database_password:$('#database_password').val(),
                    step:'step1',
                },
            })
            .done(function(data) {

                error= parseInt(data.error);
                if(error != 1 && error !=0){
                    $('#msg').removeClass('success');
                    $('#msg').addClass('error');
                    $('#msg').html(data);
                }
                else if(error ===0)
                {
                    $('#msg').addClass('success');
                    $('#msg').html(data.msg);
                    setTimeout('generateKey()', 4000);
                }else{
                    $('#msg').removeClass('success');
                    $('#msg').addClass('error');
                    $('#msg').html(data.msg);
                }
            })
            .fail(function() {
                $('#msg').removeClass('success');
                $('#msg').addClass('error');
                $('#msg').html('<?php echo e(trans('language.install.env.error'), false); ?>');
                // $(this).button('reset');
            })
    }
});

function generateKey(){
    $('#msg').removeClass('error');
    $('#msg').removeClass('success');
    $('#msg').html('<?php echo e(trans('language.install.key.process'), false); ?>');
    $.ajax({
        url: 'install.php',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step2'},
    })
    .done(function(data) {

        $('#msg').removeClass('success');
        $('#msg').removeClass('error');
        error = parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').addClass('error');
            $('#msg').html(data);
        }
        else if(error ===0)
        {
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            setTimeout('installDatabase()', 4000);
        }else{
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('<?php echo e(trans('language.install.key.error'), false); ?>');
    })
}

function installDatabase(){
    $('#msg').removeClass('error');
    $('#msg').removeClass('success');
    $('#msg').html('<?php echo e(trans('language.install.database.process'), false); ?>');
    $.ajax({
        url: 'install.php',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step3'},
    })
    .done(function(data) {

        $('#msg').removeClass('success');
        $('#msg').removeClass('error');
         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').addClass('error');
            $('#msg').html(data);
        }
        else if(error === 0)
        {
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            setTimeout('setPermission()', 4000);
        }else{
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('<?php echo e(trans('language.install.database.error'), false); ?>');
    })
}

function setPermission(){
    $('#msg').removeClass('error');
    $('#msg').removeClass('success');
    $('#msg').html('<?php echo e(trans('language.install.permission.process'), false); ?>');
    $.ajax({
        url: 'install.php',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step4'},
    })
    .done(function(data) {

        $('#msg').removeClass('success');
        $('#msg').removeClass('error');
         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').addClass('error');
            $('#msg').html(data);
        }
        else if(error ===0)
        {
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            window.location.replace($('#admin_url').val());
        }else{
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
        }
    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('<?php echo e(trans('language.install.permission.error'), false); ?>');
    })
}

function validateForm(){
        $("#formInstall").validate({
        rules: {
            "database_host": {
                required: true,
            },
            "database_port": {
                required: true,
                number:true,
            },
            "admin_url": {
                required: true,
            },
            "database_name": {
                required: true,
            },
            "database_user": {
                required: true,
            },
        },
        messages: {
            "database_host": {
                required: "<?php echo e(trans('language.install.validate.database_host_required'), false); ?>",
            },
            "database_port": {
                required: "<?php echo e(trans('language.install.validate.database_port_required'), false); ?>",
                number: "<?php echo e(trans('language.install.validate.database_port_number'), false); ?>",
            },
            "admin_url": {
                required: "<?php echo e(trans('language.install.validate.admin_url_required'), false); ?>",
            },
            "database_name": {
                required: "<?php echo e(trans('language.install.validate.database_name_required'), false); ?>",
            },
            "database_user": {
                required: "<?php echo e(trans('language.install.validate.database_user_required'), false); ?>",
            }
        }
    }).valid();
}

</script>

</body>
</html>
