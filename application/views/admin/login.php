<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pu-hey Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Timeline CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/timeline.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/admin/css/sb-admin-2.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=base_url()?>assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>

	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading" style="text-align:center;">
                        <img src="<?=base_url()?>assets/logo.png" alt="" width="200px" />
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" onsubmit="return form_onsubmit();" >
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?=isset($cookie->email)?$cookie->email:""?>" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="<?=isset($cookie->password)?$cookie->password:""?>">
                                </div>
                                <!--div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me" <?=isset($cookie->remember)?"checked":""?>>Remember Me
                                    </label>
                                </div-->
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-primary btn-block">登入</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/sb-admin-2.js"></script>
</body>
</html>
