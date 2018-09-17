 

	</div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?=base_url()?>assets/admin/js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/morris/raphael.min.js"></script>
    <script src="<?=base_url()?>assets/admin/js/plugins/morris/morris.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/sb-admin-2.js"></script>

	<script src="<?=base_url()?>assets/admin/js/custom.js"></script>
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
		});
	</script>
	<style>

	</style>
</body>

</html>
