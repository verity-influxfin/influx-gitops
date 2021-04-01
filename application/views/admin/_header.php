<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>inFlux Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/admin/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />

    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/admin/css/sb-admin-2.css?t=2" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=base_url()?>assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url()?>assets/admin/css/datepicker.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/admin/css/bootstrap-datetimepicker.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/admin/css/bootstrap-table.css" rel="stylesheet">
	<link href="<?=base_url()?>assets/admin/css/bootstrap-table-filter-control.css" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="//cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	
	<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
	<!-- /#wrapper -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<script src="//cloud.tinymce.com/stable/tinymce.min.js?apiKey=50g7aczgyla2r7aenym5m6qorvpgpbo0mjec0fffvlt9frf6"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/bootstrap-table.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/bootstrap-table-zh-TW.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/bootstrap-table-export.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/bootstrap-table-filter-control.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/admin/scripts/moment.min.js"></script>

	<script>
		$(document).ready(function() {
			
			$('[data-toggle="datepicker"]').datepicker({
			  format: 'yyyy-mm-dd',
			});
			
			$('.fancyframe').fancybox({
				'type':'iframe',
			});

            var RotateImage = function (instance) {
                this.instance = instance;

                this.init();
            };

            $.extend(RotateImage.prototype, {
                $button_left: null,
                $button_right: null,
                transitionanimation: true,

                init: function () {
                    var self = this;

                    self.$button_right = $('<button data-rotate-right class="fancybox-button fancybox-button--rotate" title="Rotate to right"><span class="oi oi-action-redo"></span></button>')
                        .prependTo(this.instance.$refs.toolbar)
                        .on('click', function (e) {
                            self.rotate('right');
                        });

                    self.$button_left = $('<button data-rotate-left class="fancybox-button fancybox-button--rotate" title="Rotate to left"><span class="oi oi-action-undo"></span></button>')
                        .prependTo(this.instance.$refs.toolbar)
                        .on('click', function (e) {
                            self.rotate('left');
                        });
                },

                rotate: function (direction) {
                    var self = this;
                    var image = self.instance.current.$image[0];
                    var angle = parseInt(self.instance.current.$image.attr('data-angle')) || 0;

                    if (direction == 'right') {
                        angle += 90;
                    } else {
                        angle -= 90;
                    }

                    if (!self.transitionanimation) {
                        angle = angle % 360;
                    } else {
                        $(image).css('transition', 'transform .3s ease-in-out');
                    }

                    self.instance.current.$image.attr('data-angle', angle);

                    $(image).css('webkitTransform', 'rotate(' + angle + 'deg)');
                    $(image).css('mozTransform', 'rotate(' + angle + 'deg)');
                }
            });

            $(document).on('onInit.fb', function (e, instance) {
                if (!instance.opts.rotate) {
                    instance.Rotate = new RotateImage(instance);
                }
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
