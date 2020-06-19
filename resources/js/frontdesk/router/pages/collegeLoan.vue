<template>
  <div class="college-wrapper">
    <banner :data="this.bannerData" :isShowLoan="true"></banner>
    <experience ref="experience" title="聽聽大家怎麼說" :data="this.experiences"></experience>
    <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
    <join href="./Image/child_banner.jpg" :isShowLoan="true" subTitle="加入普匯完成你的目標吧！"></join>
    <qa :data="this.qaData" title="常見問題"></qa>
    <videoShare ref="videoShare" title="Follow普匯小學堂<br>增進科普金融知識" :data="this.video"></videoShare>
  </div>
</template>

<script>
import videoShareComponent from "./component/videoShareComponent";
import experienceComponent from "./component/experienceComponent";
import bannerComponent from "./component/bannerComponent";
import joinComponent from "./component/joinComponent";
import applyDescribeComponent from "./component/applyDescribeComponent";
import qaComponent from "./component/qaComponent";

export default {
  components: {
    videoShare: videoShareComponent,
    experience: experienceComponent,
    banner: bannerComponent,
    join: joinComponent,
    applyDescribe: applyDescribeComponent,
    qa: qaComponent
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {}
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        if (row.type === "college") {
          data.push(row);
        }
      });

      return data;
    },
    video() {
      return this.$store.getters.VideoData.slice(0,4);
    }
  },
  created() {
    this.$store.dispatch("getExperiencesData");
    this.$store.dispatch("getVideoData", { category: "share" });
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`學生貸款 - ${$("title").text()}`);
  },
  mounted() {
    $(this.$refs.videoShare.$refs.share_content).attr("data-aos", "fade-left");
    $(this.$refs.experience.$refs.experience_slick).attr("data-aos", "zoom-in");
    $(this.$refs.apply.$refs.apply_slick).attr("data-aos", "fade-up");
    AOS.init();
  },
  watch: {
    experiences() {
      this.$nextTick(() => {
        this.$refs.experience.createSlick();
      });
    }
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "college" }).then(res => {
        this.bannerData = res.data;
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "college" }).then(res => {
        this.applyData = res.data;
        this.$nextTick(() => {
          this.$refs.apply.createSlick();
        });
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "college" }).then(res => {
        this.qaData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
@media (max-width: 1023px) {
  .college-wrapper {
    .loan-banner img {
      bottom: 1%;
    }
  }
}
</style>
