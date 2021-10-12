<?php
// print_r($papago['sub_data']);exit;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>授信審核表</title>
</head>
<body>
  <!-- 圖片 -->
  <div><?= $main_title?></div>
  <div>
    <a href="<?= isset($main_image) ? $main_image : '';?>" data-fancybox="images">
      <img src="<?= isset($main_image) ? $main_image : '';?>" style='width:100%;max-width:300px'>
    </a>
  </div>
  <div><?= $sub_title?></div>
  <div>
    <a href="<?= isset($sub_image) ? $sub_image: '';?>" data-fancybox="images">
      <img src="<?= isset($sub_image) ? $sub_image: '';?>" style='width:100%;max-width:300px'>
    </a>
  </div>
  <!-- 比對結果 -->
  <div>
    <!-- Azure -->
    <div style="margin-bottom:20px">
      <div>Azure 面孔比對結果：</div>
      <!-- 比對照片1 -->
      <div><?= $main_title ?>人臉數：<?= ! empty($azure['main_data']) ? count($azure['main_data']) : '-';?>人臉</div>
      <?php
        if(!empty($azure['main_data'])){
		  if(!empty($azure['main_data']['faceRectangle'])){
			  foreach($azure['main_data'] as $k=>$v){
                $num = $k+1;
                echo"<div>{$main_title}座標{$num}：X： {$v['faceRectangle']['top']} Y： {$v['faceRectangle']['left']} W： {$v['faceRectangle']['height']} H： {$v['faceRectangle']['width']} </div>";
              }
		  }else{
			  if(!empty($azure['main_data']['error'])){
				  echo"<div>錯誤訊息 code：{$azure['main_data']['error']['code']} message：{$azure['main_data']['error']['message']}<div>";
			  }
		  }
        }
      ?>
      <!-- <div>***照1座標：X： 987 Y： 654 W： 321 H： 123 </div>
      <div>***照2座標：X： 987 Y： 654 W： 321 H： 123 </div> -->
      <!-- 比對照片2 -->
      <div><?= $sub_title ?>人臉數：<?= ! empty($azure['sub_data']) ? count($azure['sub_data']) : '-';?>人臉</div>
      <?php
        if(!empty($azure['sub_data'])){
		  if(!empty($azure['sub_data']['faceRectangle'])){
	          foreach($azure['sub_data'] as $k=>$v){
	            $num = $k+1;
	            echo"<div>{$sub_title}座標{$num}：X： {$v['faceRectangle']['top']} Y： {$v['faceRectangle']['left']} W： {$v['faceRectangle']['height']} H： {$v['faceRectangle']['width']} </div>";
	          }
		  }else{
			  if(!empty($azure['sub_data']['error'])){
				  echo"<div>錯誤訊息 code：{$azure['sub_data']['error']['code']} message：{$azure['sub_data']['error']['message']}<div>";
			  }
		  }
        }
      ?>
      <!-- 相似度對照表 -->
      <?= isset($azure['table']) ? $azure['table'] : '';?>
      <!-- <table border="1">
        <tbody>
          <tr>
            <td></td><td>***照1</td><td>***照2</td>
          </tr>
          <tr>
            <td>*照1</td><td>87</td><td>38</td>
          </tr>
        </tbody>
      </table> -->
    </div>
    <!-- Face++ -->
    <div style="margin-bottom:20px">
      <div>Face++ 面孔比對結果：</div>
      <div><?= $main_title ?> 與 <?= $sub_title ?> 相似度：<?= isset($faceplusplus['compare']) ? $faceplusplus['compare'] : '-';?></div>
    </div>
    <!-- Face8 -->
    <div style="margin-bottom:20px">
      <div>Face8 面孔比對結果：</div>
      <!-- 比對照片1 -->
      <div><?= $main_title ?>人臉數：<?= ! empty($papago['main_data']['faces']) ? count($papago['main_data']['faces']) : '-';?>人臉</div>
      <?php
        if(!empty($papago['main_data']['faces'])){
          foreach($papago['main_data']['faces'] as $k=>$v){
            $num = $k+1;
            echo"<div>{$main_title}座標{$num}：X： {$v['face_rectangle']['top']} Y： {$v['face_rectangle']['left']} W： {$v['face_rectangle']['height']} H： {$v['face_rectangle']['width']} </div>
            <div>{$main_title}座標{$num}活體值：{$v['attributes']['liveness']['value']}(閥值：{$v['attributes']['liveness']['threshold']})</div>";
          }
        }
      ?>
      <!-- <div>***照1座標：X： 987 Y： 654 W： 321 H： 123 </div>
      <div>***照1活體值：0.00040677213(閥值：0.97)</div>
      <div>***照2座標：X： 987 Y： 654 W： 321 H： 123 </div>
      <div>***照2活體值：0.9999573(閥值：0.97)</div> -->
      <!-- 比對照片2 -->
      <div><?= $sub_title ?>人臉數：<?= ! empty($papago['sub_data']['faces']) ? count($papago['sub_data']['faces']) : '-';?>人臉</div>
      <?php
        if(!empty($papago['sub_data']['faces'])){
          foreach($papago['sub_data']['faces'] as $k=>$v){
            $num = $k+1;
            echo"<div>{$sub_title}座標{$num}：X： {$v['face_rectangle']['top']} Y： {$v['face_rectangle']['left']} W： {$v['face_rectangle']['height']} H： {$v['face_rectangle']['width']} </div>
            <div>{$sub_title}座標{$num}活體值：{$v['attributes']['liveness']['value']}(閥值：{$v['attributes']['liveness']['threshold']})</div>";
          }
        }
      ?>
      <!-- <div>*照1座標：X： 987 Y： 654 W： 321 H： 123 </div>
      <div>***照1活體值：0.00040677213(閥值：0.97)</div> -->
      <!-- 相似度對照表 -->
      <?= isset($papago['table']) ? $papago['table'] : '';?>
      <!-- <table border="1">
        <tbody>
          <tr>
            <td></td><td>***照1</td><td>***照2</td>
          </tr>
          <tr>
            <td>*照1</td><td>87</td><td>38</td>
          </tr>
        </tbody>
      </table> -->
    </div>
  </div>
</body>
</html>
