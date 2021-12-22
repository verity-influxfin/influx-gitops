<template>
  <form class="sme-consult" @submit.prevent="onSubmit">
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
        <label class="input-group-text">目前營運上遇到的困難</label>
      </div>
      <select class="custom-select">
        <option selected></option>
        <option value="text" v-for="text in option1" :key="text">
          {{ text }}
        </option>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">公司資金用途</label>
      </div>
      <select class="custom-select">
        <option selected></option>
        <option value="text" v-for="text in option2" :key="text">
          {{ text }}
        </option>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">融資時最常遇到的困難</label>
      </div>
      <select class="custom-select">
        <option selected></option>
        <option value="text" v-for="text in option3" :key="text">
          {{ text }}
        </option>
      </select>
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
  data() {
    return {
      option1: [
        '資金周轉問題', '經營管理問題', '開發難度高',
        '成本費用入不敷出', '產品競爭力不足', '其他'
      ],
      option2: [
        '營運週轉', '償還借款', '購置設備廠房', '其他'
      ],
      option3: [
        '資料提供複雜且不便利', '核准率低', '申請、審核時間過長', '核准條件較差，資金成本高', '其他'
      ]
    }
  },
  methods: {
    onSubmit() {
      this.$router.push({ name: 'end', params: { type: 'consult' } })
    }
  },
}
</script>

<style lang="scss" scoped>
.sme-consult {
  padding: 50px 80px;
  margin: 0 auto;
  max-width: 900px;
  .input-group-text {
    width: 200px;
  }
}
@media screen and (max-width: 767px) {
  .sme-consult {
    padding: 30px 15px;
    max-width: min-content;
    .input-group-text {
      width: 85vw;
    }
  }
}
</style>
