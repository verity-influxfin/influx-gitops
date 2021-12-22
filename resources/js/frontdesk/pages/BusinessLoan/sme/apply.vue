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
        <label class="input-group-text">1.統一編號</label>
      </div>
      <input
        type="text"
        class="form-control"
        v-model="tax_id"
        @input="getCompanyName"
      />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">2.公司名稱</label>
      </div>
      <input type="text" class="form-control" v-model="company_name" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">3.聯絡人</label>
      </div>
      <input type="text" class="form-control" v-model="contact_person" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">4.聯絡電話</label>
      </div>
      <input type="tel" class="form-control" v-model="contact_phone" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">5.電子信箱</label>
      </div>
      <input type="email" class="form-control" v-model="email" />
    </div>
    <div>
      <button type="submit" class="btn btn-secondary btn-block">確認</button>
    </div>
  </form>
</template>

<script>
import axios from 'axios'
export default {
  mounted() {
    $(document).on('keyup keypress', 'form input[type="text"]', function (e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        return false;
      }
    });
  },
  data() {
    return {
      tax_id: '',
      company_name: '',
      contact_person: '',
      contact_phone: '',
      email: ''
    }
  },
  methods: {
    onSubmit() {
      const { tax_id, company_name, contact_person, contact_phone, email } = this
      axios({
        method: 'post',
        url: '/api/v1/saveApplyForm',
        data: {
          tax_id,
          company_name,
          contact_person,
          contact_phone,
          email
        }
      }).then(({ data }) => {
        if (data.result === 'ERROR') {
          alert(data.message)
          return
        }
        this.$router.push({ name: 'end', params: { type: 'apply' } })
      })
    },
    getCompanyName() {
      const { tax_id } = this
      if (tax_id.length > 7) {
        axios({
          method: 'get',
          url: '/api/v1/getCompanyName?tax_id=' + tax_id,
        }).then(({ data }) => {
          if (data?.data) {
            this.company_name = data?.data
          }
        })
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.sme-apply {
  padding: 50px 80px;
  margin: 0 auto;
  max-width: 900px;
  .input-group-text {
    width: 100px;
    background-color: #fff;
    border: none;
  }
  .btn-block {
    margin-left: 100px;
    width: calc(100% - 100px);
    background-color: #326398;
    color: #fff;
  }
}
@media screen and (max-width: 767px) {
  .sme-apply {
    padding: 30px 15px;
    max-width: min-content;
    .input-group-text {
      width: 85vw;
    }
    .btn-block {
      margin-left: 0;
      width: 100%;
    }
  }
}
</style>
