<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>P2P Lending</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Timeline CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/timeline.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>assets/admin/css/sb-admin-2.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="<?=base_url()?>assets/admin/css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=base_url()?>assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-body">
						<? if(!empty($image) && !empty($image2)){?>
							<? if(!empty($image)){?>
								<div class="form-group">
									<span>證件照 偵測到 <?=$image_count?> 張臉</span>
									<span>相似度 <?=$image_answer?></span>
                                    <img src="<?=$image?>" style="width:80%">
                                </div>
							<?}?>
							<div class="form-group">
							<? 
							if($id_card && !empty($id_card['id_number'])){
								$face = $id_card['face'];
								unset($id_card['face']);
								foreach($id_card as $key=> $value){
							?>
									<span><?=$key?> : <?=$value?></span><br>
							<?}?>
								<img src="data:image/jpg;base64,<?=$face?>" alt="" style="width:20%"/>
							<? }else{ ?>
								<span>辨識失敗</span>
							<?}?>
							 </div>
							<? if(!empty($image2)){?>
								<div class="form-group">
									<span>個人與證件自拍 偵測到 <?=$image2_count?> 張臉</span>
									<span>相似度 <?=$image2_answer?></span>
                                    <img src="<?=$image2?>" style="width:80%">
                                </div>
							<?}?>
						
							<? if(!empty($answer)){?>
								<div class="form-group">
									<span>交叉比對結果：</span><br>
									<?foreach($answer as $key => $value){
										echo "<span>".$value."</span><br>";
									}?>
                                </div>
							<?}?>
							<div class="form-group">
									<a href="http://p2p-api.clockin.com.tw/" target="_self">再測一次</a>
                            </div>
							
						<?}else{?>
                        <form role="form" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
									<span>證件照</span>
                                    <input class="form-control" name="image" type="file" accept="image/*" capture>
                                </div>
                                <div class="form-group">
									<span>個人與證件自拍</span>
                                    <input class="form-control" name="image2" type="file" accept="image/*" capture> 
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">送出</a>
                            </fieldset>
                        </form>
						<?}?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="<?=base_url()?>assets/admin/js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/plugins/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/admin/js/sb-admin-2.js"></script>
</body>
</html>
