<template>
  <div class="info-card">
    <div class="picture">
      <img
        :src="$props.userData.picture ? $props.userData.picture : `./Images/mug_shot.svg`"
        class="img-fluid"
      />
    </div>
    <div class="userInfo">
      <p>{{regards}}</p>
      <span>我的使用者編號：{{$props.userData.id}}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ["userData"],
  computed: {
    regards() {
      let time = parseInt(new Date().getHours());
      let text = "";
      if (0 <= time && time < 5) {
        text = "晚安";
      } else if (5 <= time && time < 11) {
        text = "早安";
      } else if (11 <= time && time < 17) {
        text = "午安";
      } else if (17 <= time && time < 23) {
        text = "晚安";
      }
      return `${text} ${this.$props.userData.name}`;
    }
  }
};
</script>