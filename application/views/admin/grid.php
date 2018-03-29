<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>P2P Lending</title>
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
	<!-- jQuery Version 1.11.0 -->
    <script src="<?=base_url()?>assets/admin/js/jquery-1.11.0.js"></script>
</head>
<body>
	
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-body">
						<button class="btn-facebook">登入非死不可</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<script>
		jQuery(document).on("click", ".btn-facebook", function () {
        FB.login(function (response) {
          if (response.status === 'connected') {
			  var fbid = response.authResponse.userID;
			  var token = response.authResponse.accessToken;
			  console.log(response.authResponse);
			 FB.api(
			  '/'+fbid+'/friends',
			  'GET',
			  {},
			  function(response) {
				console.log(response);
			  }
			);
          } else if (response.status === 'not_authorized') {
          } else {
          }
        }, {scope: 'public_profile,email,user_friends,user_posts'});
        return false;
    });
	</script>
	
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = 'https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.12&appId=999193623552932&autoLogAppEvents=1';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
		window.fbAsyncInit = function() {
		FB.init({
			appId      : '999193623552932',
			xfbml      : true,
			cookie     : true,
			version    : 'v2.12'
		});
	}; 
	
	</script>

    
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/sb-admin-2.js"></script>
</body>
</html>
