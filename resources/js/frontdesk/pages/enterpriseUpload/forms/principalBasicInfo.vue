<template>
  <div class="principal-basic-info">
    <div class="uploadform-content-title mb-3 row no-gutters">
      <div class="col-auto">個人基本資料</div>
      <div class="col"></div>
      <div class="col-auto tabs d-flex">
        <div
          class="tab-item"
          :class="{ active: tab === 1 }"
          @click="changeTab(1)"
        >
          個人基本資料
        </div>
        <div
          class="tab-item"
          :class="{ active: tab === 2 }"
          @click="changeTab(2)"
        >
          個人不動產
        </div>
      </div>
    </div>
    <div class="uploadform-content-border passbook-content">
      <form v-if="tab === 1" @submit.prevent="tab = 2">
        <div class="row no-gutters mb-3 pb-2 title-border-bottom">
          <div class="col-auto form-item-title">1.個人基本資料</div>
          <div class="col"></div>
          <div class="col-auto uploadform-content-title-hint">
            *為必要填寫欄位
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(1)*負責人學歷：</div>
          <div class="d-flex">
            <label>
              <input
                type="radio"
                v-model="formData.edu"
                value="1"
                name="edu"
                required
              />
              小學
            </label>
            <label class="mx-2">
              <input type="radio" v-model="formData.edu" value="2" name="edu" />
              國中
            </label>
            <label>
              <input type="radio" v-model="formData.edu" value="3" name="edu" />
              高中職
            </label>
            <label class="mx-2">
              <input type="radio" v-model="formData.edu" value="4" name="edu" />
              專科
            </label>
            <label>
              <input type="radio" v-model="formData.edu" value="5" name="edu" />
              大學
            </label>
            <label class="mx-2">
              <input type="radio" v-model="formData.edu" value="6" name="edu" />
              碩士
            </label>
            <label>
              <input type="radio" v-model="formData.edu" value="7" name="edu" />
              博士
            </label>
            <label class="mx-2">
              <input type="radio" v-model="formData.edu" value="8" name="edu" />
              無
            </label>
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(2)*負責人擔任本企業負責人年度：</div>
          <div>
            <input type="text" class="form-control w-50" required />
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(3)*負責人從事本行業年度：</div>
          <div class="d-flex">
            <input type="text" class="form-control w-50" required />
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(4)*負責人行動電話：</div>
          <div class="d-flex">
            <input type="text" class="form-control w-50" placeholder="請輸入行動電話" required />
          </div>
        </div>
        <div class="pb-3">
          <div class="form-item-title">(5)*負責人Email：</div>
          <div class="d-flex">
            <input type="email" class="form-control w-50" placeholder="請輸入Email" required />
          </div>
        </div>
        <button type="submit" ref="submit1" hidden></button>
      </form>
      <form v-if="tab === 2">
        <div class="row no-gutters mb-3 pb-2 title-border-bottom">
          <div class="col-auto form-item-title">2.個人不動產</div>
          <div class="col"></div>
          <div class="col-auto uploadform-content-title-hint">
            *為必要填寫欄位
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(1)持有人名稱：</div>
          <div>
            <input type="text" class="form-control w-50" />
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(2)地址名稱：</div>
          <div>
            <input type="text" class="form-control w-50" />
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(3)使用狀況：</div>
          <div class="d-flex">
            <label>
              <input type="radio" />
              自用
            </label>
            <label class="mx-2">
              <input type="radio" />
              出租
            </label>
            <label>
              <input type="radio" />
              閒置
            </label>
          </div>
        </div>
        <div class="mb-3">
          <div class="form-item-title">(4)不動產設定情形：</div>
          <div class="d-flex">
            <label>
              <input type="radio" />
              有
            </label>
            <label class="mx-2">
              <input type="radio" />
              無
            </label>
          </div>
        </div>
      </form>
    </div>
    <div class="row no-gutters mt-3 justify-content-end">
      <button
        class="btn uploadform-btn-primary"
        @click="changeTab(2)"
        v-if="tab === 1"
      >
        下一頁
      </button>
      <button
        class="btn uploadform-btn-primary"
        @click="onSubmit"
        v-if="tab === 2"
      >
        返回
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
  data() {
    return {
      tab: 1,
      formData: {
        edu: 0
      }
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
        console.log(file)
      }
    },
    changeTab(tab) {
      if (tab === 2) {
        this.$refs.submit1.click()
        return
      }
      this.tab = 1
    },
    onSubmit() {
      this.$router.push('/enterprise-upload/overview/principal?case-id=' + this.caseId)
    }
  },
}
</script>

<style lang="scss" scoped>
.principal-basic-info {
  .passbook-content {
    min-height: 482px;
  }
  .tabs {
    border: 1px solid #036eb7;
    border-radius: 6px;
    .tab-item {
      padding: 4px 51px;
      font-weight: 500;
      font-size: 14px;
      line-height: 20px;
      text-align: center;
      color: #036eb7;
      &.active {
        color: #fff;
        background: #036eb7;
      }
    }
  }
  .title-border-bottom {
    border-bottom: 1.5px solid #f3f3f3;
  }
  .form-item-title {
    margin-bottom: 6px;
    font-weight: 500;
    font-size: 16px;
    line-height: 23px;
    color: #393939;
  }
}
</style>
