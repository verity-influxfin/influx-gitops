<script type="text/javascript">
    function failed(id,target_no){
        if(confirm(target_no+" 確認退件？案件將自動取消")){
            if(id){
                var p 		= prompt("請輸入退案原因，將自動通知使用者，不通知請按取消","");
                var remark 	= "";
                if(p){
                    remark = encodeURIComponent(p);
                }
                $.ajax({
                    url: '<?=admin_url('target/verify_failed?id=')?>'+id+'&remark='+remark,
                    type: 'GET',
                    success: function(response) {
                        alert(response);
                        location.reload();
                    }
                });
            }
        }
    }
</script>
<div id="page-wrapper">
	<div id="sme-loan">
		<div class="row">
			<div class="col-12">
				<h1 class="page-header">微企貸轉人工</h1>
			</div>
		</div>
		<div class="panel panel-default mt-4">
			<div class="panel-heading p-4">
                微企貸轉人工
			</div>
			<div class="panel-body">
				<table id="sme-table">
					<thead>
						<tr>
                            <th>使用者編號</th>
                            <th>案號</th>
                            <th>名稱</th>
							<th>授信審核表</th>
<!--							<th>送件審核表</th>-->
							<th>備註</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
                    <?php
                    if(isset($list) && !empty($list)){
                        $count = 0;
                        foreach($list as $key => $value) {
                        $count++;
                    ?>
                        <tr class="<?=$count%2==0?"odd":"even"; ?> list <?= $value->id??'' ?>">
                            <td>
                                <?= $value->user_id ?>
                            </td>
							<td>
								<?= $value->target_no ?>
							</td>
							<td>
                                <?= $product_list[$value->product_id]['name'].($value->sub_product_id!=0?'/'.$sub_product_list[$value->sub_product_id]['identity'][$product_list[$value->product_id]['identity']]['name']:'').(preg_match('/'.$subloan_list.'/',$value->target_no)?'(產品轉換)':'') ?>
							</td>
							<td>
								<a class="btn btn-primary btn-info" href="/admin/creditmanagementtable/report?target_id=<?= $value->id??'' ?>&table_type=management" target="_blank" >查看<br />授信審核表</a>
							</td>
<!--							<td>-->
<!--								<a href="">-->
<!--									<button class="btn btn-info">-->
<!--										查看送件審核表-->
<!--									</button>-->
<!--								</a>-->
<!--							</td>-->
							<td>
								轉人工原因：<?= $value->target_data['manual_reason'] ?? '' ?>
							</td>
							<td>
								<button class="btn btn-primary" disabled>轉換為一般企業融資</button>
                                <button class="btn btn-danger" onclick="failed(<?= ($value->id ?? "").',\''.($value->target_no ?? '').'\''?>)" >退件</button>
							</td>
						</tr>
                    <?php }
                    }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		const t = $('#sme-table').DataTable({
			'ordering': false,
			"paging": false,
			"info": false,
			'language': {
				'processing': '處理中...',
				'lengthMenu': '顯示 _MENU_ 項結果',
				'zeroRecords': '目前無資料',
				'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
				'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
				'infoFiltered': '(從 _MAX_ 項結果過濾)',
				'search': '使用本次搜尋結果快速搜尋',
				'paginate': {
					'first': '首頁',
					'previous': '上頁',
					'next': '下頁',
					'last': '尾頁'
				}
			},
			"info": false
		})
	})
</script>
