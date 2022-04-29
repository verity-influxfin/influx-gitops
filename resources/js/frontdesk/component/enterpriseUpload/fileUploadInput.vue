<template>
  <div>
    <div class="row no-gutters justify-content-center mb-2">
      <div class="col-auto">
        <input
          type="file"
          ref="fileUpload"
          @change="onUpload(e)"
          hidden
          :multiple="multiple"
          :accept="accept"
        />
      </div>
      <div class="col-auto">
        <div class="filename" v-for="item in fileNames">{{ item }}</div>
      </div>
    </div>
    <div class="row no-gutters">
      <div class="col-auto mr-2">
        <button class="btn btn-upload-primary" @click="onClickFile">上傳檔案</button>
      </div>
      <div class="col-auto">
        <button class="btn btn-upload-secondary" @click="onClickFile">重新修改</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    multiple: {
      type: Boolean,
      default: false
    },
    accept: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      files: [],
      fileNames: [
        '未選擇任何檔案'
      ]
    }
  },
  methods: {
    onClickFile() {
      this.$refs.fileUpload.click()
    },
    onUpload() {
      this.fileNames = []
      this.files = []
      const currentFiles = this.$refs.fileUpload.files
      if (currentFiles.length === 0) {
        this.fileNames = ['未選擇任何檔案']
      } else {
        for (const file of currentFiles) {
          this.fileNames.push(file.name)
          this.files.push(file)
        }
        // this.$refs.fileUpload.values = ''
        this.$emit('change', this.files)
      }
    }
  }

}
</script>

<style lang="scss" scoped>
.btn-upload-primary {
  padding: 4px 22px;
  font-size: 14px;
  line-height: 20px;
  color: #ffffff;
  background: #036eb7;
  border-radius: 6px;
}
.btn-upload-secondary {
  padding: 4px 22px;
  font-size: 14px;
  line-height: 20px;
  color: #ffffff;
  background: #f29600;
  border-radius: 6px;
}
.filename {
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 20px;
  padding: 4px 0;
  color: #393939;
}
</style>
