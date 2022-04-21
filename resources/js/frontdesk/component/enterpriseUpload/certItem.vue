<template>
  <div class="cert-item">
    <div class="icon-block">
      <img :src="icon" class="icon-img" />
      <span class="text-center" v-html="iconText"></span>
    </div>
    <div class="d-flex align-items-center">
        <div class="v-divider"></div>
    </div>
    <div class="content-block col p-0">
      <slot name="content">
        <div class="default-success">
          已完成提供，通過驗證
          <img src="@/asset/images/enterpriseUpload/success-check.svg" class="ml-1" />
        </div>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: ''
    },
    iconText: {
      type: String,
      default: ''
    },
  },
}
</script>

<style lang="scss" scoped>
.cert-item {
  display: flex;
  padding: 21px;
  border: 1.5px solid #f3f3f3;
  box-sizing: border-box;
  border-radius: 10px;
  .icon-block {
    width: 160px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-direction: column;
  }
  .v-divider {
    border-left: 1.5px solid #f3f3f3;
    height: 60px;
    margin: 0 28px;
  }
  .content-block {
    display: flex;
    align-items: center;

  }
  .default-success {
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 26px;
    margin: 0 auto;
    color: #393939;
  }
}
</style>
