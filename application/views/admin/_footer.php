	</div>

	
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/morris/raphael.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/plugins/morris/morris.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/sb-admin-2.js"></script>
	<script src="<?=base_url()?>assets/admin/js/datepicker.js"></script>
	<script>			
		$(document).ready(function() {
			$('#dataTables-tables').dataTable({
				"bPaginate": false, // 顯示換頁
				"searching": true, // 顯示搜尋
				"info":	false, // 顯示資訊
				"fixedHeader": true, // 標題置頂
				"dom": '<"pull-left"f><"pull-right"l>tip',
				"oLanguage":{
					"sProcessing":"處理中...",
					"sLengthMenu":"顯示 _MENU_ 項結果",
					"sZeroRecords":"目前無資料",
					"sInfo":"顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
					"sInfoEmpty":"顯示第 0 至 0 項結果，共 0 項",
					"sInfoFiltered":"(從 _MAX_ 項結果過濾)",
					"sSearch":"模糊搜尋:",
					"oPaginate":{"sFirst":"首頁",
						"sPrevious":"上頁",
						"sNext":"下頁",
						"sLast":"尾頁"}
                }
			});
			
			$('#dataTables-paging').dataTable({
				"bPaginate": true, // 顯示換頁
				"searching": true, // 顯示搜尋
				"info":	true, // 顯示資訊
				"fixedHeader": true, // 標題置頂
				"dom": '<"pull-left"f><"pull-right"l>tip',
				"oLanguage":{
					"sProcessing":"處理中...",
					"sLengthMenu":"顯示 _MENU_ 項結果",
					"sZeroRecords":"目前無資料",
					"sInfo":"顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
					"sInfoEmpty":"顯示第 0 至 0 項結果，共 0 項",
					"sInfoFiltered":"(從 _MAX_ 項結果過濾)",
					"sSearch":"模糊搜尋:",
					"oPaginate":{"sFirst":"首頁",
						"sPrevious":"上頁",
						"sNext":"下頁",
						"sLast":"尾頁"}
                }
			});
			
			$('[data-toggle="datepicker"]').datepicker({
			  format: 'yyyy-mm-dd',
			});
		});

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
		
		function ValidateNumber(e, pnumber)
		{
			if (!/^\d+$/.test(pnumber))
			{
				$(e).val(/^\d+/.exec($(e).val()));
			}
			return false;
		}

	</script>
</body>

</html>
