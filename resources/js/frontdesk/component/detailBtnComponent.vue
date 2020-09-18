<template>
  <div :class="['btn', btnColor(), 'btn-sm', {'float-left':$props.left},{'col-sm-12':!$props.left}]" @click="send">
    <img class="img-fluid" :src="`/images/${btnIcon()}`" />
    <span>{{btnText()}}</span>
  </div>
</template>

<script>
export default {
  props: ["data", "left"],
  methods: {
    btnColor() {
      let blueList = [0, 1, 2, 3, 4];

      let colorList = {
        8: "btn-secondary",
        9: "btn-danger",
        10: "btn-success"
      };

      if (blueList.indexOf(this.$props.data.status) !== -1) {
        return "btn-primary";
      } else if (this.$props.data.status === 5) {
        if (7 <= this.$props.data.delay_days ) {
          return "btn-danger";
        } else if ( 0 < this.$props.data.delay_days && this.$props.data.delay_days < 7) {
          return "btn-warning";
        } else {
          return "btn-primary";
        }
      } else {
        return colorList[this.$props.data.status];
      }
    },
    btnIcon() {
      let deepblueList = [0, 1, 2, 3, 4];
      let errorList = [8, 9];

      if (deepblueList.indexOf(this.$props.data.status) !== -1) {
        return "icon_arrow_deepblue.svg";
      } else if (errorList.indexOf(this.$props.data.status) !== -1) {
        return "icon_error.svg";
      } else if (this.$props.data.status === 5 && this.$props.data.delay_days > 0) {
        return "icon_warning_white.svg";
      } else {
        return "icon_ok.svg";
      }
    },
    btnText() {
      let textList = {
        0: "身分驗證中",
        1: "待簽約",
        2: "系統核可中",
        3: "系統媒合中",
        4: "系統媒合中",
        5: "分期付款中",
        20: "訂單處理中",
        21: "待申請分期",
        22: "身分驗證中",
        23: "出貨進行中",
        24: "出貨進行中",
        8: "申請取消",
        9: "申請失敗",
        10: "已結案"
      };
      if (this.$props.data.status === 5 && this.$props.data.delay_days > 0) {
        return `已逾期${this.$props.data.delay_days}日`;
      } else if (
        this.$props.data.status === 10 &&
        this.$props.data.sub_status === 2
      ) {
        return "產品轉換完成";
      } else if (
        this.$props.data.status === 10 &&
        this.$props.data.sub_status === 4
      ) {
        return "提前還款完成";
      } else {
        return textList[this.$props.data.status];
      }
    },
    send() {
      this.$emit("sendinfo", this.data.id,this.data.status);
    }
  }
};
</script>

<style>
</style>