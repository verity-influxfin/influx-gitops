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
        <button class="ml-3 btn btn-return" @click="$router.push('/enterprise-upload/home')">
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
              :icon="require('@/asset/images/enterpriseUpload/company-1.svg')"
              icon-text="(變更事項)設立登記表"
              :certification="certification.governmentauthorities"
            >
              <template
                v-slot:content
                v-if="certification.governmentauthorities.user_status === null"
              >
                <div
                  class="
                    row
                    no-gutters
                    align-items-center
                    justify-content-center
                    w-100
                    h-100
                  "
                >
                  <div class="col-auto cert-content-text text-warning">
                    <div>尚未完成公司實名認證</div>
                    <div>請至「普匯inFlux」APP完成</div>
                  </div>
                </div>
              </template>
            </cert-item>
          </div>
          <div class="mt-3">
            <cert-item
              :icon="require('@/asset/images/enterpriseUpload/company-2.svg')"
              icon-text="公司授權核實"
              :certification="certification.judicialguarantee"
            >
              <template
                v-slot:content
                v-if="certification.judicialguarantee.user_status === null"
              >
                <div
                  class="
                    row
                    no-gutters
                    align-items-center
                    justify-content-center
                    w-100
                    h-100
                  "
                >
                  <div class="col-auto cert-content-text text-warning">
                    <div>尚未完成公司實名認證</div>
                    <div>請至「普匯inFlux」APP完成</div>
                  </div>
                </div>
              </template>
            </cert-item>
          </div>
        </div>
      </div>
      <div class="mt-5 white-block">
        <div
          class="mask"
          v-if="
            certification.governmentauthorities.user_status === null ||
            certification.judicialguarantee.user_status === null
          "
        ></div>
        <div>
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-3.svg')"
            icon-text="公司基本資料"
            :certification="certification.profilejudicial"
          >
            <template
              v-slot:content
              v-if="certification.profilejudicial.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.公司基本資料
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-basic-info?case-id=' +
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
            :icon="require('@/asset/images/enterpriseUpload/company-4.svg')"
            icon-text="近六個月公司往來<br>存摺封面 + 內頁"
            :certification="certification.passbookcashflow"
          >
            <template
              v-slot:content
              v-if="certification.passbookcashflow.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.近六個月主要帳戶存摺封面<br />
                  2.近六個月主要帳戶存摺內頁
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-passbook?case-id=' +
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
            :icon="require('@/asset/images/enterpriseUpload/company-5.svg')"
            icon-text="近三年<br>401/403/405"
            :certification="certification.businesstax"
          >
            <template
              v-slot:content
              v-if="certification.businesstax.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.近三年401/403/405表
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-sales-info?case-id=' +
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
            :icon="require('@/asset/images/enterpriseUpload/company-6.svg')"
            icon-text="近12個月員工投保<br>人數資料"
            :certification="certification.employeeinsurancelist"
          >
            <template
              v-slot:content
              v-if="certification.employeeinsurancelist.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.近12個月員工投保單位人數資料<br />
                  2.具結書(若無需成立投保單位)
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-insurance-info?case-id=' +
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
            :icon="require('@/asset/images/enterpriseUpload/company-7.svg')"
            icon-text="近三年所得稅<br>結算申報書"
            :certification="certification.incomestatement"
          >
            <template
              v-slot:content
              v-if="certification.incomestatement.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col d-flex align-items-center cert-content-text">
                  1.近三年稅簽-損益表<br />
                  2.近三年稅簽-資產負債表
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-income-info?case-id=' +
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
            :icon="require('@/asset/images/enterpriseUpload/company-8.svg')"
            icon-text="聯合徵信報告＋A13<br>第二聯"
            :certification="certification.investigationjudicial"
          >
            <template
              v-slot:content
              v-if="certification.investigationjudicial.user_status === null"
            >
              <div class="row no-gutters w-100 h-100">
                <div class="col cert-content-text">
                  1.公司聯徵報告 <br />
                  2.加查 「<span style="color: red">A13、J21、G27、B06</span>」
                  <br />
                  3.請上傳「申請信用報告收執聯」<br />
                  4.郵寄至：<br />
                  <small>
                    10486台北市中山區松江路111號11樓之1
                    <br />普匯金融科技股份有限公司
                  </small>
                </div>
                <div class="col-auto d-flex align-items-end">
                  <router-link
                    :to="
                      '/enterprise-upload/form/company-credit-info?case-id=' +
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

export default {
  components: {
    caseOverview,
    progressOverview,
    certItem
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
