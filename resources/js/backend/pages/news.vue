<template>
  <div class="bk-news-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">最新消息</li>
      </ol>
    </nav>

    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
      <div class="input-group float-right" style="width: 300px">
        <input
          type="text"
          class="form-control"
          placeholder="最新消息標題"
          v-model="filter.title"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>

    <div class="news-block">
      <div class="news-tabletitle">
        <div class="post-title">消息標題</div>
        <div class="image">圖片</div>
        <div class="status">狀態</div>
        <div class="order">置頂</div>
        <div class="post_date">刊登時間</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="news-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="news-modal modal fade"
      ref="newsModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn btn-close" data-dismiss="modal">
              <i class="far fa-times-circle"></i>
            </button>
            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">消息標題</span>
              </div>
              <input
                type="text"
                class="form-control"
                placeholder="文章標題"
                v-model="postTitle"
              />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">消息進版圖</span>
              </div>
              <div style="display: grid">
                <img :src="upLoadImg" class="img-fluid" style="width: 300px" />
                <input type="file" @change="fileChange" />
              </div>
            </div>
            <div class="input-group" style="width: 15%">
              <div class="input-group-prepend">
                <span class="input-group-text">是否公開</span>
              </div>
              <select class="custom-select" v-model="status">
                <option value="on">公開</option>
                <option value="off">不公開</option>
              </select>
            </div>
            <div class="input-group" style="width: 15%">
              <div class="input-group-prepend">
                <span class="input-group-text">是否置頂</span>
              </div>
              <select class="custom-select" v-model="order">
                <option value="1">是</option>
                <option value="0">否</option>
              </select>
            </div>
            <ckeditor v-model="postContent" :config="editorConfig"></ckeditor>
          </div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-secondary float-left" data-dismiss="modal">
              取消
            </button>
            <button class="btn btn-success float-right" @click="submit">
              送出
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="messageModal-modal modal fade"
      ref="messageModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
      data-backdrop="static"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">{{ message }}</div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-success float-right" @click="close">
              確認
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let newsRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="news-row">
        <div class="post-title">{{item.post_title}}</div>
        <div class="image"><img :src="item.image_url" class="img-fluid"></div>
        <div class="status">{{item.status === 'on' ? '公開': '不公開'}}</div>
        <div class="order">{{item.order === '0' ? '否': '是'}}</div>
        <div class="post_date">{{item.post_date}}</div>
        <div class="action-row">
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
    </li>
  `,
});

export default {
  data: () => ({
    status: "",
    order: "",
    ID: "",
    actionType: "",
    upLoadImg: "",
    postTitle: "",
    postContent: "",
    message: "",
    rawData: [],
    filtedData: [],
    filter: {
      title: "",
    },
    editorConfig: {
      height: 500,
      filebrowserImageUploadUrl: "uploadNewsImg",
      filebrowserUploadMethod: "form",
      image_previewText: "",
    },
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getNewsData();
  },
  methods: {
    async getNewsData() {
      axios
        .get("getNews")
        .then((res) => {
          this.rawData = res.data;
          this.filtedData = res.data;
          this.pagination();
        })
        .catch((error) => {
          console.log("getNews 發生錯誤，請稍後再試");
        });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filtedData,
          pageSize: 8,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new newsRow({
                propsData: {
                  item,
                  vm: $this,
                },
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
        });
      });
    },
    async fileChange(e) {
      let imageData = new FormData();
      imageData.append("file", e.target.files[0]);
      let res = await axios.post("uploadNewsIntroImg", imageData);
      this.upLoadImg = `/upload/news/${res.data}`;
    },
    create() {
      this.postTitle = "";
      this.status = "off";
      this.order = "0";
      this.postContent = "";
      this.ID = "";
      this.actionType = "insert";
      this.upLoadImg = "/images/default-image.png";

      $(this.$refs.newsModal).modal("show");
    },
    edit(item) {
      this.postTitle = item.post_title;
      this.status = item.status;
      this.order = item.order;
      this.postContent = item.post_content;
      this.ID = item.ID;
      this.upLoadImg = item.image_url
        ? item.image_url
        : "/images/default-image.png";
      this.actionType = "update";

      $(this.$refs.newsModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteNews", {
          ID: item.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.getNewsData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyNews", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            post_title: this.postTitle,
            post_content: this.postContent,
            status: this.status,
            order: this.order,
            image_url: this.upLoadImg,
          },
        })
        .then((res) => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getNewsData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(
            `${
              this.actionType === "insert" ? "新增" : "更新"
            }發生錯誤，請稍後再試`
          );
        });
    },
    close() {
      $(this.$refs.newsModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    },
  },
};
</script>

<style lang="scss">
.bk-news-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .news-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .news-tabletitle {
      display: flex;
      overflow: auto;

      div {
        border-bottom: 2px solid #929292;
        text-align: center;
        padding: 10px;

        &:not(:last-child) {
          border-right: 1px solid #bbbbbb;
        }
      }
    }

    .news-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .news-row {
        display: flex;

        .action-row {
          padding: 5px;
        }

        div {
          padding: 10px;
          &:not(:first-child) {
            text-align: center;
          }

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .news-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .post-title {
      width: 50%;
      text-align: start;
    }

    .image {
      width: 18%;
    }

    .order {
      width: 5%;
    }

    .status {
      width: 5%;
    }

    .post_date {
      width: 12%;
    }

    .action-row {
      width: 10%;
    }
  }

  .news-modal {
    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
      }
    }

    .input-group {
      padding-bottom: 20px;
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;
  }

  .btn-close {
    position: absolute;
    top: -9px;
    right: -7px;
    font-size: 27px;
    z-index: 1;
  }
}
</style>