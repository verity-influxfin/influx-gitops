<template>
  <div class="principal-passbook">
    <div class="uploadform-content-title mb-3">
      <div class="col-auto">聯合徵信報告＋Ａ11 第二聯</div>
    </div>
    <div class="row no-gutters uploadform-content-border passbook-content">
      <div class="col uploadform-divider-right d-flex align-items-center">
        <div>
          <div class="mb-3">資料提供說明</div>
          <div class="mb-3">上傳文件：</div>
          <div>1.負責人聯徵報告</div>
          <div>2.加查「<span style="color: red">A11、J10、J03</span>」</div>
          <div>3.請上傳「申請信用報告收執聯」</div>
          <div class="mb-3">
            4.郵寄至：
            <small>
              10486台北市中山區松江路111號11樓之1 普匯金融科技股份有限公司
            </small>
          </div>
          <div class="mb-3">上傳檔案：</div>
          <div>1.需為PNG、JPG、PDF檔(不超過10M)</div>
        </div>
      </div>
      <div class="col d-flex align-items-center justify-content-center pl-4">
        <file-upload-input @change="onFileChange" multiple />
      </div>
    </div>
    <div class="row no-gutters mt-3 justify-content-end">
      <button class="btn uploadform-btn-primary" @click="onSubmit">
        確認儲存
      </button>
    </div>
  </div>
</template>

<script>
import fileUploadInput from '@/component/enterpriseUpload/fileUploadInput'

export default {
  components: {
    fileUploadInput,
  },
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    }
  },
  methods: {
    onFileChange(files) {
      for (const file of files) {
        console.log(file)
      }
    },
    onSubmit() {
      this.$router.push('/enterprise-upload/overview/principal?case-id=' + this.caseId)
    }
  },
}
</script>

<style lang="scss" scoped>
.principal-passbook {
  .passbook-content {
    height: 482px;
  }
}
</style>
