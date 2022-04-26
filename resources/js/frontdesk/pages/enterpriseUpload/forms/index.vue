<template>
  <div class="uploadform-index">
    <div class="header"></div>
    <div class="uploadform-content">
      <div class="left">
        <div>
          <case-overview />
        </div>
        <div>
          <progressOverview />
        </div>
      </div>
      <div class="right">
        <div class="white-block">
          <router-view></router-view>
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
  mounted() {
    this.getApplyInfo()
  },
  data() {
    return {
      companyData: {
        icon: require('@/asset/images/enterpriseUpload/company-1.svg'),
        iconText: '12'
      }
    }
  },
  methods: {
    getApplyInfo() {
      this.$store.dispatch('enterprise/updateCaseOverview', { id: this.caseId })
    }
  },
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    }
  }
}
</script>
<style lang="scss">
.uploadform-content-title {
  font-weight: 500;
  font-size: 24px;
  line-height: 35px;
  color: #393939;
}
.uploadform-content-title-hint {
  font-weight: 500;
  font-size: 14px;
  line-height: 20px;
  color: #036eb7;
}
.uploadform-content-border {
  border: 1.5px solid #f3f3f3;
  border-radius: 10px;
  padding: 35px 21px;
}
.uploadform-divider-right {
  border-right: 1.5px solid #f3f3f3;
}
.uploadform-content-info {
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 20px;
  color: #393939;
}
.uploadform-btn-primary {
  padding: 4px 22px;
  font-size: 14px;
  line-height: 20px;
  text-align: center;
  color: #ffffff;
  background: #036eb7;
  border-radius: 6px;
  border: 1.5px solid #036eb7;
  &:hover {
    color: #036eb7;
    background: #fff;
  }
}
</style>
<style lang="scss" scoped>
.uploadform-index {
  .header {
    margin-bottom: 50px;
    width: 100%;
    height: 200px;
    background-image: url('~images/enterpriseUploadBanner.png');
    border-radius: 10px;
  }
  .uploadform-content {
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
    }
  }
}
</style>
