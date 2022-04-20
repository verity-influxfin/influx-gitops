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
        <button class="ml-3 btn btn-return">返回</button>
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
            />
          </div>
          <div class="mt-3">
            <cert-item
              :icon="require('@/asset/images/enterpriseUpload/company-2.svg')"
              icon-text="公司授權核實"
            />
          </div>
        </div>
      </div>
      <div class="mt-5 white-block">
        <div>
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-3.svg')"
            icon-text="公司基本資料"
          ></cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-4.svg')"
            icon-text="近六個月公司往來<br>存摺封面 + 內頁"
          ></cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-5.svg')"
            icon-text="近三年<br>401/403/405"
          ></cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-6.svg')"
            icon-text="近12個月員工投保<br>人數資料"
          ></cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-7.svg')"
            icon-text="近三年所得稅<br>結算申報書"
          ></cert-item>
        </div>
        <div class="mt-3">
          <cert-item
            :icon="require('@/asset/images/enterpriseUpload/company-8.svg')"
            icon-text="聯合徵信報告＋Ａ13<br>第二聯"
          ></cert-item>
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
  data() {
    return {
      companyData: {
        icon: require('@/asset/images/enterpriseUpload/company-1.svg'),
        iconText: '12'
      }
    }
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
  }
}
</style>
