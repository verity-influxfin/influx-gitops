<template>
  <div class="credit-card">
    <div class="credit-box">
      <template v-if="text">
        <p class="t-t" v-html="text"></p>
      </template>
      <template v-else>
        <p class="t-t">
          只要有一支手機，「10分鐘」完成申貸手續、「30分鐘」核准、「60分鐘」到帳，<br />
          全程AI自動審核、無人照會打擾，<br />
          完全符合現代年輕人希望簡單、快速、尊重隱私的生活需求！
        </p>
      </template>
      <div class="t-c"><h2>月付金額試算</h2></div>
      <div class="hr"></div>
      <template v-if="$props.license">
        <br />
        <p class="license">*{{ $props.license }}</p>
      </template>
      <div class="credit-slider">
        <creditBoard 
        :amount="amount"
        minRate="5"
        maxRate="16"
        color="#170e9c" />
      </div>
    </div>
  </div>
</template>

<script>
import creditBoard from "./svg/creditBoardComponent";

export default {
  components: { creditBoard },
  props: ["amount", "license", "text"],
};
</script>

<style lang="scss">
.credit-card {
  overflow: auto;
  padding: 4rem 8rem;
  position: relative;
  background: #ecedf1;

  .credit-box {
    .t-c {
      margin: 0px auto;
    }

    .hr {
      margin: 1rem auto;
    }

    .t-t {
      font-size: 22px;
      line-height: 2.27;
      letter-spacing: 1.1px;
      text-align: left;
      color: #143970;
      font-weight: bold;
      margin-bottom: 5rem;
    }

    .license {
      color: #4f4f4f;
      font-size: 11px;
      margin: 0px;
    }

    .credit-slider {
      width: 800px;
      margin: 0px auto;
    }
  }
}

@media screen and (max-width: 767px) {
  .credit-card {
    padding: 20px 10px 0px 10px;

    .credit-box {
      .t-t {
        line-height: initial;
        font-size: 17px;
        letter-spacing: initial;
        margin-bottom: 2rem;
      }

      .credit-slider {
        width: 100%;
      }
    }
  }
}
</style>
