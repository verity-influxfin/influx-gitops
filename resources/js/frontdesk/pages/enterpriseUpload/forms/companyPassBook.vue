<template>
  <div class="principal-passbook">
    <div class="uploadform-content-title mb-3">公司資料更新</div>
    <div class="row no-gutters uploadform-content-border passbook-content">
      <div
        class="
          col
          d-flex
          align-items-center
          justify-content-center
          uploadform-divider-right
          mr-4
        "
      >
        <div>
          <div class="info-group">
            <img src="@/asset/images/enterpriseUpload/company-form-2.svg" />
            <div>近六個月公司往來<br />存摺封面 + 內頁</div>
          </div>
          <file-upload-input @change="onFileChange" multiple />
        </div>
      </div>
      <div class="col d-flex align-items-center">
        <div>
          <div class="mb-3">資料提供說明</div>
          <div class="mb-3">上傳文件：</div>
          <div>1.近六個月存摺封面</div>
          <div class="mb-3">2.近六個月存摺內頁</div>
          <div class="mb-3">上傳檔案：</div>
          <div>1.需為PNG、JPG、PDF檔(不超過10M)</div>
          <div>2.電子存摺亦可</div>
        </div>
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
      this.$router.push('/enterprise-upload/overview/company?case-id=' + this.caseId)
    }
  },
}
</script>

<style lang="scss" scoped>
.principal-passbook {
  .passbook-content {
    height: 482px;
  }
  .info-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1.5px solid #f3f3f3;
    margin-bottom: 20px;
  }
}
</style>
