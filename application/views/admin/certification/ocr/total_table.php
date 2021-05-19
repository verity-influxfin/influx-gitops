<style>

 .total-table tr td:not(:first-child){
   text-align: center;
 }
</style>
<table class="table table-striped table-bordered table-hover dataTable total-table">
  <tbody>
    <?php
    if(! empty($data)){
      foreach($data as $v){
        if(! empty($v['title']) && ! empty($v['value'])){
          $title = isset($v['title']) ? $v['title'] : '';
          echo '<tr><td>'.$title.'</td>';
            foreach($v['value'] as $v1){
              $value = isset($v1) ? $v1 : '';
              echo'<td>'.$value.'</td>';
            }
          echo'</tr>';
      }}
    }
    ?>
  </tbody>
</table>
