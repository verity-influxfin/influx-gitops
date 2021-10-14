<form role="form" action="<?= admin_url( isset($data['upload_location']) ? $data['upload_location'] : '' ) ?>" method="post" enctype="multipart/form-data">
  <div>檔案名稱:</div>
  <input type="file" name="file_upload_tmp[]" multiple accept="<?= isset($data['file_type']) ? $data['file_type'] :'*'; ?>"/>
  <br/>
  <?php
    if(isset($data['extra_info'])){
      foreach($data['extra_info'] as $k=>$v){
        echo '<input style="display:none;" type="text" name="'.$k.'" value="'.$v.'"/>';
      }
    }
  ?>
  <input type="submit" class="btn btn-primary" value="上傳" />
</form>
