<template>
  <form class="sme-apply" @submit.prevent="onSubmit">
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
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">需求額度</label>
      </div>
      <select class="custom-select">
        <option selected></option>
        <option value="1">100萬內</option>
        <option value="2">100萬</option>
        <option value="3">100萬以上</option>
      </select>
    </div>
    <div>
      <button type="submit" class="btn btn-secondary btn-block">確認</button>
    </div>
  </form>
</template>

<script>
export default {
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
