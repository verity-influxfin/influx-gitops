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
            if($case_info){
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
      if(!isset($list['status']) && $_GET['cer']){
          echo '<table class="table table-bordered margin-bottom:50px"><tbody><tr><td>裁判字號</td><td>臺灣臺北地方法院 109 年度 易 字第 2150 號民事判決（6K）</td></tr><tr><td>裁判案號</td><td>2150</td></tr><tr><td>裁判法院</td><td>臺灣新北地方法院</td></tr><tr><td>裁判日期</td><td>2018-03-16</td></tr><tr><td>裁判案由</td><td>本票裁定</td></tr><tr><td colspan="2">臺灣新北地方法院簡易庭民事裁定　　 109年度司票字第2150號 聲　請　人　東元資融股份有限公司  法定代理人　周佳琳　 相　對　人　李玉婷　 上列當事人間聲請對本票准許強制執行事件，本院裁定如下：主  文 相對人於民國一百零八年一月三日簽發本票內載憑票於民國一百 零八年九月三日無條件支付聲請人新臺幣（下同）參萬元，其中 之壹萬貳仟伍佰元及自民國一百零八年九月三日起至清償日止， 按年息百分之二十計算之利息，得為強制執行。 聲請程序費用伍佰元由相對人負擔。    理  由 一、本件聲請意旨以：聲請人執有相對人簽發如主文所示之本票    ，付款地為本院轄區，經到期後提示尚有如主文所示之請求    金額及利息未獲清償，為此提出本票一件，聲請裁定准許強    制執行。 二、本件聲請核與票據法第123條規定相符，應予准許。 三、依非訟事件法第21條第2 項、民事訴訟法第78條，裁定如主     文。 四、如對本裁定抗告，應於裁定送達後10日內向本院提出抗告狀     ，並繳納抗告費新臺幣1,000元。 五、發票人如主張本票係偽造、變造者，應於接到本裁定後20日     之不變期間內，對執票人向本院另行提起確認之訴。 六、發票人已提確認之訴者，得依非訟事件法第195條規定聲請     法院停止執行。</td></tr></tbody></table>';
      }else{
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
            if($case_info){
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