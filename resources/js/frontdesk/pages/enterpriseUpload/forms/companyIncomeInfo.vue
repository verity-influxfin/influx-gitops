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
         <file-upload-input @change="onFileChange" accept=".jpg,.png,.pdf" />
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
      <button
        class="btn uploadform-btn-primary"
        @click="onSubmit"
        :disabled="file.size === 0"
      >
        確認儲存
      </button>
    </div>
  </div>
</template>

<script>
import fileUploadInput from '@/component/enterpriseUpload/fileUploadInput'
import Axios from 'axios'

export default {
  components: {
    fileUploadInput,
  },
  data() {
    return {
      file: new File([], ''),
    }
  },
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    }
  },
  methods: {
    onFileChange(files) {
      for (const file of files) {
        this.file = file
      }
    },
    async onSubmit() {
      const formData = new FormData()
      if (this.file.type.includes('pdf')) {
        formData.append('pdf', this.file)
        const { data } = await Axios({
          method: 'POST',
          url: '/api/v1/user/upload_pdf',
          data: formData,
          mimeType: 'multipart/form-data',
        })
        await Axios.post('/api/v1/certification/judicial_file_upload', {
          // incomestatement
          certification_id: '1002',
          file_list: data.pdf_id
        })
        this.$router.push('/enterprise-upload/overview/principal?case-id=' + this.caseId)
      } else {
        formData.append('image', this.file)
        try {
          const { data } = await Axios({
            method: 'POST',
            url: '/api/v1/user/upload',
            data: formData,
            mimeType: 'multipart/form-data',
          })
          await Axios.post('/api/v1/certification/judicial_file_upload', {
            // incomestatement
            certification_id: '1002',
            file_list: data.image_id
          })
          this.$router.push('/enterprise-upload/overview/principal?case-id=' + this.caseId)
        } catch (error) {
          console.error(error)
        }
      }
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
