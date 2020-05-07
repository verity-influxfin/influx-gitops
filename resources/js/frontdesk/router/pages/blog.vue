<template>
  <div class="blog-wrapper">
    <div class="blog-content" v-html="this.pageHtml"></div>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
export default {
  data: () => ({
    pageHtml: ""
  }),
  computed: {
    knowledge() {
      return this.$store.getters.KnowledgeData;
    }
  },
  created() {
    $("title").text(`influx 小學堂 - inFlux普匯金融科技`);
    this.$store.dispatch("getKnowledgeData");
  },
  watch: {
    knowledge() {
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
          dataSource: $this.knowledge,
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
.blog-wrapper {
  padding: 30px;
  overflow: auto;

  .blog-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;

    .card {
      margin: 10px;
      width: 48%;
      padding: 20px;
      float: left;
    }

    .gray {
      color: #9a9a9a;
    }

    a {
      color: green;

      &:hover {
        text-decoration: none;
      }
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 1023px) {
    .blog-content {
      width: 95%;

      .card {
        width: 46%;
      }
    }
  }

  @media (max-width: 767px) {
    .blog-content {
      width: 100%;

      .card {
        width: 96%;
      }
    }
  }
}
</style>