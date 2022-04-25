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
            <img src="@/asset/images/enterpriseUpload/company-form-5.svg" />
            <div>近三年所得稅</div>
            <div>結算申報書</div>
          </div>
          <file-upload-input @change="onFileChange" multiple />
        </div>
      </div>
      <div class="col d-flex align-items-center">
        <div>
          <div class="mb-3">資料提供說明</div>
          <div class="mb-3">上傳文件：</div>
          <div>1.近三年稅簽-損益表</div>
          <div class="mb-3">2.近三年稅簽-資產負債表</div>
          <div class="mb-3">上傳檔案：</div>
          <div>1.需為PNG、JPG、PDF檔(不超過10M)</div>
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
