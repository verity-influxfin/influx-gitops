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
    <p class="error-message">{{$props.errorMessage}}</p>
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
    message: ""
  }),
  methods: {
    submit() {
      let data = {
        name: this.name,
        email: this.email,
        phone: this.phone,
        message: this.message
      };

      this.$emit("submit", data);
    }
  }
};
</script>

<style lang="scss">
.input-from {
  width: 80%;
  margin: 0px auto;
  padding: 10px;
  border-radius: 10px;
  background: #ffedc6;
  box-shadow: 0 0 6px black;

  .input-group {
    margin-bottom: 15px;
  }

  textarea {
    height: 150px;
  }

  .error-message {
    text-align: center;
    color: red;
    font-size: 14px;
  }

  .memo {
    color: #ffffff;
    font-size: 15px;
    font-weight: bolder;
    text-shadow: 0 0 3px black;
  }

  .btn-submit {
    display: block;
    margin: 0px auto;
    background: #002bff;
    color: #ffffff;
    font-weight: bold;
    width: 150px;

    &:hover {
      border: 2px solid #002bff;
      background: #ffffff;
      color: #002bff;
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