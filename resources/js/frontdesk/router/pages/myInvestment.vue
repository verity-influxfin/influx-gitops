<template>
  <div>投資專區...</div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  created() {
    $("title").text(`投資專區 - inFlux普匯金融科技`);
  },
};
</script>

<style>
</style>