<template>
  <div class="company-overview">
    <div class="left">
      <div>
        <case-overview />
      </div>
      <div>
        <progressOverview />
      </div>
      <div>
        <button
          class="ml-3 btn btn-return"
          @click="$router.push('/enterprise-upload/home')"
        >
          返回
        </button>
      </div>
    </div>
    <div class="right">
      <div class="white-block">
        <div class="row no-gutters justify-content-between align-items-end">
          <div class="block-title">負責人資料更新</div>
          <div class="block-info">*完成提供並 ⌜確認送出⌟ 後系統即開始驗證</div>
        </div>
        <div class="mt-3">
          <div>
            <cert-item
              :icon="require('@/asset/images/enterpriseUpload/principal-1.svg')"
              icon-text="個人實名認證"
              :certification="certification.idcard"
            />
          </div>
        </div>
      </div>
      <div class="mt-5 white-block">
        <div
          class="mask"
          v-if="certification.idcard.user_status === null"
        ></div>
        <div>
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/principal-2.svg')"
            icon-text="常用電子信箱"
            :certification="certification.email"
          >
            <template
              v-slot:content
              v-if="certification.email.user_status === null"
            >
              <div class="w-100">
                <div class="row no-gutters cert-content-text">
                  請輸入常用Email：
                </div>
                <form class="row no-gutters mt-2" @submit.prevent="submitEmail">
                  <div class="col mr-4">
                    <input
                      type="email"
                      class="input-mail w-100"
                      v-model="email"
                      required
                      placeholder="請輸入"
                    />
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-next-page">發送驗證信件</button>
                  </div>
                </form>
              </div>
            </template>
          </cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/principal-3.svg')"
            icon-text="個人基本資料"
            :certification="certification.profile"
          >
            <template
              v-slot:content
              v-if="certification.profile.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.個人基本資料<br />
                  2.個人不動產使用情形
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/principal-basic-info?case-id=' +
                      caseId
                    "
                  >
                    <button class="btn btn-next-page">提供更新</button>
                  </router-link>
                </div>
              </div>
            </template>
          </cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/principal-4.svg')"
            icon-text="存摺"
            :certification="certification.simplificationfinancial"
          >
            <template
              v-slot:content
              v-if="certification.simplificationfinancial.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.近六個月主要帳戶存摺封面<br />
                  2.近六個月主要帳戶存摺內頁
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/principal-passbook?case-id=' +
                      caseId
                    "
                  >
                    <button class="btn btn-next-page">提供更新</button>
                  </router-link>
                </div>
              </div>
            </template>
          </cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/principal-5.svg')"
            icon-text="個人所得資料"
            :certification="certification.simplificationjob"
          >
            <template
              v-slot:content
              v-if="certification.simplificationjob.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.勞保異動明細<br />
                  2.近一年各類所得資料清單<br />
                  3.近一年扣繳憑單<br />
                  (3擇1)
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/principal-income-info?case-id=' +
                      caseId
                    "
                  >
                    <button class="btn btn-next-page">提供更新</button>
                  </router-link>
                </div>
              </div>
            </template>
          </cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/principal-6.svg')"
            icon-text="聯合徵信報告＋Ａ11<br>第二聯"
            :certification="certification.investigationa11"
          >
            <template v-slot:content>
              <div
                class="row no-gutters w-100 h-100"
                v-if="certification.investigationa11.user_status === null"
              >
                <div class="col cert-content-text">
                  1.負責人聯徵報告<br />
                  2.加查「<span style="color: red">A11、J10、J03</span>」<br />
                  3.請上傳「申請信用報告收執聯」<br />
                  4.郵寄至：<br />
                  <small
                    >10486台北市中山區松江路111號11樓之1<br />
                    普匯金融科技股份有限公司
                  </small>
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/principal-credit-info?case-id=' +
                      caseId
                    "
                  >
                    <button class="btn btn-next-page">提供更新</button>
                  </router-link>
                </div>
              </div>
            </template>
          </cert-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import caseOverview from '@/component/enterpriseUpload/caseOverview'
import progressOverview from '@/component/enterpriseUpload/progressOverview'
import certItem from '@/component/enterpriseUpload/certItem'
import Axios from 'axios'
export default {
  components: {
    caseOverview,
    progressOverview,
    certItem
  },
  data() {
    return {
      email: ''
    }
  },
  methods: {
    submitEmail() {
      Axios.post('/api/v1/certification/email', { email: this.email }).then(() => {
        alert('已發送驗證信至您的信箱')
        this.$store.dispatch('enterprise/updateCaseOverview', { id: this.caseId })
      })
    }
  },
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    },
    certification() {
      return this.$store.state.enterprise.certifications
    },
  },
}
</script>

<style lang="scss" scoped>
.company-overview {
  display: grid;
  grid-template-columns: 56fr 100fr;
  gap: 50px;
  .left {
    display: flex;
    flex-direction: column;
    gap: 50px;
  }
  .btn-return {
    padding: 4px 44px;
    background: #f29600;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    color: #ffffff;
    border-radius: 6px;
  }
  .white-block {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    padding: 30px 30px 15px;
    .block-title {
      font-style: normal;
      font-weight: 500;
      font-size: 24px;
      line-height: 35px;
      color: #393939;
    }
    .block-info {
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      line-height: 20px;
      color: #036eb7;
    }
    .mask {
      position: absolute;
      background: rgba(15, 15, 15, 0.25);
      border-radius: 20px;
      z-index: 20;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
  }
  .cert-content-text {
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    color: #393939;
  }
  .input-mail {
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    color: #393939;
    border: 1.5px solid #f3f3f3;
  }
  .btn-next-page {
    font-weight: 500;
    font-size: 14px;
    line-height: 20px;
    text-align: center;
    color: #ffffff;
    padding: 4px 22px;
    background: #036eb7;
    border-radius: 6px;
  }
}
</style>
