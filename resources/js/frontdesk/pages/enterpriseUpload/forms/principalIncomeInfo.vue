<template>
  <div class="principal-passbook">
    <div class="uploadform-content-title mb-3 row justify-content-end">
        <div class="col-auto">個人所得資料(非必要)</div>
        <div class="col"></div>
        <div class="col-auto uploadform-content-title-hint d-flex align-items-end">*若不提供則跳過即可</div>
    </div>
    <div class="row no-gutters uploadform-content-border passbook-content">
      <div class="col uploadform-divider-right d-flex align-items-center">
        <div>
          <div class="mb-3">資料提供說明</div>
          <div class="mb-3">上傳文件(3擇1即可)：</div>
          <div>1.勞保異動明細</div>
          <div>2.近一年所得稅各類所得資料清單</div>
          <div class="mb-3">3.近一年扣繳憑單</div>
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
