<template>
  <form class="sme-apply" @submit.prevent="onSubmit">
    <!-- prevent enter submit  -->
    <button
      type="submit"
      disabled
      style="display: none"
      aria-hidden="true"
    ></button>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">統一編號</label>
      </div>
      <input type="text" class="form-control" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">公司名稱</label>
      </div>
      <input type="text" class="form-control" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">聯絡人</label>
      </div>
      <input type="text" class="form-control" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">聯絡電話</label>
      </div>
      <input type="tel" class="form-control" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">電子信箱</label>
      </div>
      <input type="email" class="form-control" />
    </div>
    <div>
      <button type="submit" class="btn btn-secondary btn-block">確認</button>
    </div>
  </form>
</template>

<script>
export default {
  mounted() {
    $(document).on('keyup keypress', 'form input[type="text"]', function (e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        return false;
      }
    });
  },
  methods: {
    onSubmit() {

      this.$router.push({ name: 'end', params: { type: 'apply' } })
    }
  },
}
</script>

<style lang="scss" scoped>
.sme-apply {
  padding: 50px 80px;
  margin: 0 auto;
  max-width: 900px;
}
@media screen and (max-width: 767px) {
  .sme-apply {
    padding: 30px 15px;
    max-width: min-content;
    .input-group-text {
      width: 85vw;
    }
  }
}
</style>
