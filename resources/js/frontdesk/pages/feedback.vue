<template>
  <div class="feedback-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="input-from">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fas fa-user"></i>
          </span>
        </div>
        <input type="text" placeholder="名字" maxlength="20" class="form-control" v-model="name" />
      </div>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fas fa-address-card"></i>
          </span>
        </div>
        <select class="custom-select" v-model="rank">
          <option value>請選擇身分</option>
          <option value="officeWorker">上班族</option>
          <option value="student">學生</option>
        </select>
      </div>

      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fas fa-address-card"></i>
          </span>
        </div>
        <select class="custom-select" v-model="type">
          <option value>請選擇使用類別</option>
          <option value="invest">投資</option>
          <option value="loan">借款</option>
        </select>
      </div>

      <div class="input-group">
        <textarea
          type="textArea"
          maxlength="100"
          placeholder="100字以內"
          class="form-control"
          v-model="feedback"
        ></textarea>
      </div>
      <p v-if="errorMessage" class="alert alert-danger">{{errorMessage}}</p>
      <button class="btn btn-submit" @click="submit">
        送出&emsp;
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>
</template>

<script>
import bannerComponent from "../component/bannerComponent";

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  components: {
    banner: bannerComponent
  },
  data: () => ({
    name: "",
    rank:"",
    type: "",
    feedback: "",
    errorMessage: "",
    componentKey: 0,
    bannerData: {}
  }),
  created() {
    this.getBannerData();
    $("title").text(`心得回饋 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios.post(`${location.origin}/getBannerData`, { filter: "feedback" }).then(res => {
        this.bannerData = res.data;
      });
    },
    submit() {
      let { name, type,rank, feedback, $root } = this;
      let userID = $root.userData.id;

      let data = {
        name,
        rank,
        type,
        feedback,
        userID
      };

      axios
        .post("sendFeedback", data)
        .then(res => {
          this.name = "";
          this.type = "";
          this.rank = "";
          this.feedback = "";
          this.errorMessage = "";
          alert("謝謝您寶貴的意見！");
        })
        .catch(error => {
          let errorsData = error.response.data;
          let messages = [];

          $.each(errorsData.errors, (key, item) => {
            item.forEach((message, k) => {
              messages.push(message);
            });
          });

          this.errorMessage = messages.join("、");
        });
    }
  }
};
</script>

<style lang="scss">
.feedback-wrapper {
  .product-banner {
    img {
      width: 100%;
      bottom: 0%;
    }
  }

  .input-from {
    margin: 10px auto;
  }

  .alert-danger{
    text-align: center;
  }
}
</style>