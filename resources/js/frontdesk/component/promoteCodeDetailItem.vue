<template>
  <div class="detail-item">
    <div class="date">2021/10</div>
    <div class="money">$1234</div>
    <div class="icon">
      <i class="fas fa-angle-right rotate-icon"></i>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 19px 22px 19px 24px;
  border-radius: 20px;
  box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
  background-color: #fff;
  margin-bottom: 16px;
  .date {
    font-family: NotoSansTC;
    font-size: 18px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: normal;
    text-align: left;
    color: #1c395f;
  }
  .money {
    font-family: NotoSansTC;
    font-size: 18px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: normal;
    text-align: left;
    color: #f29600;
  }
  .icon {
    color: #cdcece;
  }
}
</style>