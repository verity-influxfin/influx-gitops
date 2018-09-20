<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>普匯金融科技內部管理後台</title>
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
	<link href="<?=base_url()?>assets/admin/css/datepicker.css" rel="stylesheet">
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=50g7aczgyla2r7aenym5m6qorvpgpbo0mjec0fffvlt9frf6"></script>
    <script type="text/javascript">
		$(function() {
			$('.fancyframe').fancybox({
				'type':'iframe',
			});
		});
		
		function number_only(evt)
		{
			var charCode 	= (evt.which) ? evt.which : event.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			
			return true;
		}
		
		function user_search(){
			var user_id 	= $("#user_search").val();
			if(user_id == ""){
				$(".list" ).show();
			}else{
				$(".list" ).hide();
				$("." + user_id ).show();
			}
			console.log(user_id)
		}
    </script>
</head>
<body>
    <div id="wrapper">