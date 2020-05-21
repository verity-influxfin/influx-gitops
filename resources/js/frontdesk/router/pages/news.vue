<template>
  <div class="news-page-wrapper">
    <h1>最新消息</h1>
    <ul class="news-content">
      <li v-for="(item,index) in this.news" class="card" :key="index">
        <router-link :to="item.link" style="text-align: center;">
          <img :src="item.imageSrc" class="img-custom" />
        </router-link>
        <span>{{item.title}}</span>
        <p>
          <span class="gray">Posted by</span>
          {{item.author}}
          <span class="gray">Comments:</span>
        </p>
        <p class="gray">{{item.content}}…</p>
        <router-link class="btn btn-info" :to="item.link">Read More</router-link>
      </li>
    </ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
export default {
  computed: {
    news() {
      let $this = this;
      $.each($this.$store.getters.NewsData, (index, row) => {
        $this.$store.getters.NewsData[index].content = `${row.content
          .replace(/<[^>]*>/g, "")
          .substr(0, 10)}...`;
      });
      return $this.$store.getters.NewsData;
    }
  },
  created() {
    this.$store.dispatch("getNewsData");
    $("title").text(`最新消息 - inFlux普匯金融科技`);
  },
  watch: {
    news() {
      this.pagination();
    }
  },
  mounted() {
    this.pagination();
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.news,
          callback(data, pagination) {
            data.forEach((item, index) => {
              $this.pageHtml += `
                                <div class="card">
                                    <img src="${item.imageSrc}" class="img-fluid">
                                    <h5>${item.title}</h5>
                                    <span>${item.date}</span>
                                    <p class="gray">${item.detail}</p>
                                    <a href="#${item.link}">閱讀更多》</a>
                                </div>
                            `;
            });
          }
        });
      });
    }
  }
};
</script>

<style lang="scss">
.news-page-wrapper {
  padding: 30px;
  overflow: auto;

  h1 {
    font-weight: bolder;
    text-align: center;
    margin-bottom: 30px;
  }

  .news-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .img-custom {
      height: 300px;
      max-width: 100%;
    }

    .card {
      margin: 10px;
      width: 48%;
      padding: 20px;
      float: left;
    }

    .gray {
      color: #9a9a9a;
    }

    .btn-info {
      width: fit-content;
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 1023px) {
    .news-content {
      width: 95%;

      .card {
        width: 46%;
      }
    }
  }

  @media (max-width: 767px) {
    .news-content {
      width: 100%;

      .card {
        width: 96%;
      }
    }
  }
}
</style>

