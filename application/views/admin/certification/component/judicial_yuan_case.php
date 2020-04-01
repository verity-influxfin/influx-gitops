<script>
  var page = <?=isset($case_info['page'])?$case_info['page']:'' ?>;
  var count = <?=isset($case_info['count'])?$case_info['count']:'' ?>;
  var case_name = '<?=isset($case_info['case'])?$case_info['case']:'' ?>';
  var name = '<?=isset($case_info['name'])?$case_info['name']:'' ?>';
  var total_page = '<?=isset($case_info['total_page'])?$case_info['total_page']:'' ?>';

  $(document).ready(function(){
    var next = page +1;
    var previous = page -1;

    if(page == 1){
      $('#previous-li').addClass("disabled");
    }else{
      $('#previous').attr("href", '?name='+ name +'&case='+ case_name +'&page='+ previous +'&count='+count);
    }
    if(page == total_page){
      $('#next-li').addClass("disabled");
    }else{
      $('#next').attr("href", '?name='+ name +'&case='+ case_name +'&page='+ next +'&count='+count);
    }
    if(<?isset($list['response']['verdicts'])?1:0 ?>){
      $('.case-page-list').show();
    }
  });
</script>
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">會員案件內容</h1>
    </div>
    <!-- /.col-lg-12 -->
  </div>
	<script type="text/javascript">
	
	</script>
            <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="case-page-list" style="display:none;" >
        <ul class="pagination pagination-sm no-margin pull-right">
          <li class="page-item " id="previous-li">
            <a class="page-link" aria-label="Previous" id="previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <?php
            if(isset($case_info)){
              $active_li = $case_info['page'];
              if($case_info['total_page'] <= 10){
                for($i=1; $i<=$case_info['total_page']; $i++){
                  $case_li = '<li class="page-item ';
                  if($i==$case_info['page']){
                    $case_li = $case_li .'active';
                  }
                  $case_li = $case_li.' "><a class="page-link" href="?name='.$case_info['name'].'&case='.$case_info['case'].'&page='.$i.'&count='.$case_info['count'].'">'.$i.'</a></li>';
                  echo $case_li;
                }
              }
            }
          ?>
          <li class="page-item" id="next-li">
            <a class="page-link" aria-label="Next" id="next">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </div>
      <?php
        if($list['status']==200 && isset($list['response']['verdicts'])){
          foreach($list['response']['verdicts'] as $v){
            echo'<table class="table table-bordered margin-bottom:50px"><tbody><tr><td>裁判字號</td><td>'.$v['title'].'</td></tr>'.
            '<tr><td>裁判案號</td><td>'.$v['caseNo'].'</td></tr>'.
            '<tr><td>裁判法院</td><td>'.$v['location'].'</td></tr>'.
            '<tr><td>裁判日期</td><td>'.date('Y-m-d',$v['createdAt']).'</td></tr>'.
            '<tr><td>裁判案由</td><td>'.$v['type'].'</td></tr>'.
            '<tr><td colspan="2" >'.$v['content'].'</td></tr></tbody></table>';
          }
        }
        if($list['status']==204){
          echo'<div style="font-size:36px;font-weight:bold;text-align:center;">無案件資料</div>';
        }
        if($list['status']==400){
          echo'<div style="font-size:36px;font-weight:bold;text-align:center;">無法連線</div>';
        }
      ?>
      <div class="case-page-list" style="display:none;" >
        <ul class="pagination pagination-sm no-margin pull-right">
          <li class="page-item " id="previous-li">
            <a class="page-link" aria-label="Previous" id="previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <?php
            if(isset($case_info)){
              $active_li = $case_info['page'];
              if($case_info['total_page'] <= 10){
                for($i=1; $i<=$case_info['total_page']; $i++){
                  $case_li = '<li class="page-item ';
                  if($i==$case_info['page']){
                    $case_li = $case_li .'active';
                  }
                  $case_li = $case_li.' "><a class="page-link" href="?name='.$case_info['name'].'&case='.$case_info['case'].'&page='.$i.'&count='.$case_info['count'].'">'.$i.'</a></li>';
                  echo $case_li;
                }
              }
            }
          ?>
          <li class="page-item" id="next-li">
            <a class="page-link" aria-label="Next" id="next">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- /.col-lg-12 -->
  </div>
</div>
        <!-- /#page-wrapper -->