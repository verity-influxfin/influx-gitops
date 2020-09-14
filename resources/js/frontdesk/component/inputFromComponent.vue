<template>
  <div class="input-from">
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">
          <i class="fas fa-user"></i>
        </span>
      </div>
      <input type="text" class="form-control" placeholder="Your Name" maxlength="20" v-model="name" />
    </div>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">
          <i class="fas fa-envelope"></i>
        </span>
      </div>
      <input type="text" class="form-control" placeholder="Email" maxlength="40" v-model="email" />
    </div>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">
          <i class="fas fa-phone"></i>
        </span>
      </div>
      <input
        type="text"
        class="form-control"
        placeholder="Phone Number"
        maxlength="20"
        v-model="phone"
      />
    </div>
    <div class="input-group">
      <textarea type="textArea" class="form-control" placeholder="Your Message" v-model="message" />
    </div>
    <p v-if="$props.errorMessage" class="alter alert-danger error-message">{{$props.errorMessage}}</p>
    <p class="memo">* 填寫完報名資料之後，將會有普匯金融科技公司同仁與您聯繫</p>
    <button class="btn btn-submit" @click="submit">
      送出&emsp;
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>
</template>

<script>
export default {
  props: ["errorMessage"],
  data: () => ({
    name: "",
    email: "",
    phone: "",
    message: "",
  }),
  watch: {
    phone(newdata) {
      this.phone = newdata.replace(/[^\d]/g, "");
    },
  },
  methods: {
    submit() {
      let data = {
        name: this.name,
        email: this.email,
        phone: this.phone,
        message: this.message,
      };

      this.$emit("submit", data);
    },
  },
};
</script>

<style lang="scss">
.input-from {
  width: 100%;
  margin: 0px auto;
  padding: 10px;

  .input-group {
    margin-bottom: 15px;
  }

  textarea {
    height: 150px;
  }

  .error-message {
    font-size: 16px;
    padding: 10px;
  }

  .memo {
    font-size: 15px;
    font-weight: bolder;
  }

  .btn-submit {
    display: block;
    margin: 0px auto;
    background-image: linear-gradient(to right, #fc3a3e, #f09d5d);
    color: #ffffff;
    font-weight: bold;
    width: 150px;
    border: 2px solid #ffffff;

    &:hover {
      border: 2px solid #fc3a3e;
      background: #ffffff;
      color: #fc3a3e;
    }
  }

  @media screen and (max-width: 767px) {
    width: initial;

    .memo {
      font-size: 11px;
    }
  }
}
</style>