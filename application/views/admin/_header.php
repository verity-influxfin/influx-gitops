<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>inFlux Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/admin/css/sb-admin-2.css?t=2" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=base_url()?>assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/admin/css/datepicker.css" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="//cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<!-- /#wrapper -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<script src="//cloud.tinymce.com/stable/tinymce.min.js?apiKey=50g7aczgyla2r7aenym5m6qorvpgpbo0mjec0fffvlt9frf6"></script>
	<script>
		$(document).ready(function() {
			
			$('[data-toggle="datepicker"]').datepicker({
			  format: 'yyyy-mm-dd',
			});
			
			$('.fancyframe').fancybox({
				'type':'iframe',
			});
		});
        var explode = function(){
            location.pathname=="/admin/Certification/user_bankaccount_list"?$('li[data-id=Passbook] a').click():"";
        };
        setTimeout(explode, 500);

		</script>
</head>
<body>
    <div id="wrapper">