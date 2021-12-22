<template>
  <form class="sme-consult" @submit.prevent="onSubmit">
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
        <label class="input-group-text">3.目前營運上遇到的困難</label>
      </div>
      <select class="custom-select" v-model="operating_difficulty">
        <option selected value="0"></option>
        <option
          :value="index + 1"
          v-for="(text, index) in option1"
          :key="index"
        >
          {{ text }}
        </option>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">4.公司資金用途</label>
      </div>
      <select class="custom-select" v-model="funds_purpose">
        <option selected value="0"></option>
        <option
          :value="index + 1"
          v-for="(text, index) in option2"
          :key="index"
        >
          {{ text }}
        </option>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">5.融資時最常遇到的困難</label>
      </div>
      <select class="custom-select" v-model="financing_difficulty">
        <option selected value="0"></option>
        <option
          :value="index + 1"
          v-for="(text, index) in option3"
          :key="index"
        >
          {{ text }}
        </option>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">6.聯絡人</label>
      </div>
      <input type="text" class="form-control" v-model="contact_person" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">7.聯絡電話</label>
      </div>
      <input type="tel" class="form-control" v-model="contact_phone" />
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <label class="input-group-text">8.電子信箱</label>
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
  data() {
    return {
      tax_id: '',
      company_name: '',
      operating_difficulty: 0,
      funds_purpose: 0,
      financing_difficulty: 0,
      contact_person: '',
      contact_phone: '',
      email: '',
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
      const { tax_id, company_name, contact_phone, contact_person, funds_purpose, financing_difficulty, email, operating_difficulty } = this
      axios({
        method: 'post',
        url: '/api/v1/saveConsultForm',
        data: {
          tax_id,
          funds_purpose,
          financing_difficulty,
          operating_difficulty,
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
        this.$router.push({ name: 'end', params: { type: 'consult' } })
      })
    },
    getCompanyName() {
      const { tax_id } = this
      if (tax_id.length > 7) {
        axios({
          method: 'get',
          url: '/api/v1/getCompanyName?tax_id' + tax_id,
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
.sme-consult {
  padding: 50px 80px;
  margin: 0 auto;
  max-width: 900px;
  .input-group-text {
    width: 200px;
    background-color: #fff;
    border: none;
  }
  .btn-block {
    margin-left: 200px;
    width: calc(100% - 200px);
    background-color: #326398;
    color: #fff;
  }
}
@media screen and (max-width: 767px) {
  .sme-consult {
    padding: 30px 15px;
    max-width: min-content;
    .input-group-text {
      width: 85vw;
    }
    .btn-block {
      margin-right: 0;
      width: 100%;
    }
  }
}
</style>
