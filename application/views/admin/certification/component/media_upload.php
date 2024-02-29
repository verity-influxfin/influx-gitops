<div id="media-form">
    <div>檔案名稱:</div>
    <input type="file" name="file_upload_tmp[]" id="file-picture" multiple accept="<?= isset($data['file_type']) ? $data['file_type'] :'*'; ?>" onchange="mediaUploadOnChange(event)"/>
    <br/>
    <?php
    if(isset($data['extra_info'])){
        foreach($data['extra_info'] as $k=>$v){
            echo '<input style="display:none;" class="extra_info" type="text" name="'.$k.'" value="'.$v.'"/>';
        }
    }
  ?>
  <div type="submit" id="mediaUploadBtn" class="btn btn-primary" onclick="submitMedia($(this))" disabled>上傳檔案</div>
</div>
<script src="https://unpkg.com/heic2any"></script>
<script>
    let imageFormData = [];

    function mediaUploadOnChange(event) {
        let upload_element = $(event.currentTarget);
        let $submit_btn = upload_element.parent().find('div#mediaUploadBtn');
        $submit_btn.text(`資料處理中`);
        $submit_btn.attr("disabled", "disabled");
        let allFileCount = Object.keys(event.target.files).length;
        let mediaData = [];
        Object.keys(event.target.files).forEach(async function (key) {
            let file = event.target.files[key];
            // heic 轉檔
            if(file.type.includes('hei')){
                let imageTransfer = await heic2any({ blob: file })
                var urlCreator = window.URL || window.webkitURL;
                let newPngfFile = new File([imageTransfer], file.name ,{ type: "image/png", lastModified: new Date().getTime() })
                mediaData.push(newPngfFile);
            }else{
                mediaData.push(file);
            }
            if (allFileCount == Object.keys(mediaData).length) {
                imageFormData = mediaData
                Object.setPrototypeOf(imageFormData, Object.getPrototypeOf(event.target));
                $submit_btn.text(`上傳檔案`);
                $submit_btn.removeAttr(`disabled`);

            }
        });
    }

    // 上傳檔案
    function submitMedia(selector) {
        if (Object.keys(imageFormData).length != 0) {
            var formData = new FormData();

            // 上傳檔案需要的參數
            let extraInfoItem = selector.parent("#media-form").find(".extra_info");
            $.each(extraInfoItem, function(index, item){
                formData.append($(item).attr('name'), $(item).val());
            });

            // 上傳的檔案
            Object.keys(imageFormData).forEach(function (key) {
                formData.append(`file_upload_tmp[${key}]`,imageFormData[key]);
            });
            $.ajax({
                type: "POST",
                url: `<?= admin_url($data['upload_location'] ?? '') ?>`,
                data:formData,
                processData: false,
                contentType : false,
                beforeSend: function (XMLHttpRequest){
                    selector.text(`檔案上傳中...`);
                    selector.attr("disabled", "disabled");
                },
                success: function (response) {
                    if (response.status.code == 200) {
                        alert('檔案上傳成功');
                        location.reload();
                    }else {
                        alert(response.status.message);
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = `${xhr.status}:${xhr.statusText}\n${xhr.responseText}`;
                    alert('Error - ' + errorMessage);
                    location.reload();
                }
            });
        }
    }
</script>
