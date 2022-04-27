<template>
  <div class="principal-passbook">
    <div class="uploadform-content-title mb-3 row justify-content-end">
      <div class="col-auto">個人所得資料(非必要)</div>
      <div class="col"></div>
      <div
        class="col-auto uploadform-content-title-hint d-flex align-items-end"
      >
        *若不提供則跳過即可
      </div>
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
        <file-upload-input @change="onFileChange" accept=".jpg,.png,.pdf" />
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
        await Axios.post('/api/v1/certification/natural_file_upload', {
          // simplificationjob
          certification_id: '501',
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
          await Axios.post('/api/v1/certification/natural_file_upload', {
            // simplificationjob
            certification_id: '501',
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
}
</style>
