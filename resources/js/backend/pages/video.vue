<template>
  <div class="bk-video-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">小學堂影音</li>
      </ol>
    </nav>
    <div class="action-bar">
      <button class="btn btn-primary float-left" @click="create()">
        <i class="fas fa-plus"></i>
        <span>新增</span>
      </button>
      <div class="input-group float-right" style="width: 300px;">
        <input type="text" class="form-control" placeholder="文章標題" v-model="filter.title" />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="video-block">
      <div class="video-tabletitle">
        <div class="post-title">文章標題</div>
        <div class="status">狀態</div>
        <!-- <div class="category">分類</div> -->
        <div class="order">置頂</div>
        <div class="post_modified">修改時間</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="video-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="video-modal modal fade"
      ref="videoModal"
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
            <div class="input-group" style="width: 95%;">
              <div class="input-group-prepend">
                <span class="input-group-text">文章標題</span>
              </div>
              <input type="text" class="form-control" placeholder="文章標題" v-model="postTitle" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">文章進版圖</span>
              </div>
              <div style="display: grid;">
                <img :src="upLoadImg" class="img-fluid" style="width: 300px;" />
                <input type="file" @change="fileChange" />
              </div>
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">影片連結</span>
              </div>
              <input type="text" class="form-control" placeholder="文章標題" v-model="videoLink" />
            </div>
            <div class="input-group" style="width: 15%;">
              <div class="input-group-prepend">
                <span class="input-group-text">是否公開</span>
              </div>
              <select class="custom-select" v-model="status">
                <option value="publish">公開</option>
                <option value="inherit">不公開</option>
              </select>
            </div>
            <div class="input-group" style="width: 15%;">
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
          <div class="modal-footer" style="display:block;">
            <button class="btn btn-secondary float-left" data-dismiss="modal">取消</button>
            <button class="btn btn-success float-right" @click="submit">送出</button>
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
          <div class="modal-body">{{message}}</div>
          <div class="modal-footer" style="display:block;">
            <button class="btn btn-success float-right" @click="close">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let videoRow = Vue.extend({
  props: ["item", "index", "vm"],
  template: `
    <li class="video-row">
        <div class="post-title">{{item.post_title}}</div>
        <div class="status">{{item.status === 'publish' ? '公開': '不公開'}}</div>
        <div class="order">{{item.order === '0' ? '否': '是'}}</div>
        <div class="post_modified">{{item.post_modified}}</div>
        <div class="action-row">
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(index)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(index)">刪除</button>
        </div>
    </li>
  `
});

export default {
  data: () => ({
    pageNumber:1,
    filter: "",
    postTitle: "",
    status: "publish",
    order: "0",
    postContent: "",
    actionType: "",
    message: "",
    ID: "",
    upLoadImg: "./images/default-image.png",
    videoLink: "",
    rawData: [],
    filtedData: [],
    filter: {
      title: ""
    },
    imageData: new FormData(),
    editorConfig: {
      height: 500,
      filebrowserImageUploadUrl: "uploadVideoImg",
      filebrowserUploadMethod: "form",
      image_previewText: ""
    }
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getknowledgeVideoData();
  },
  watch: {
    "filter.title"(newVal) {
      this.filtedData = [];
      this.rawData.forEach((row, index) => {
        if (row.post_title.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filtedData.push(row);
        }
      });
      this.pagination();
    }
  },
  methods: {
    getknowledgeVideoData() {
      axios
        .post("getknowledgeVideoData")
        .then(res => {
          this.rawData = res.data;
          this.filtedData = res.data;
          this.pagination();
        })
        .catch(error => {
          console.log("getknowledgeVideoData 發生錯誤，請稍後再試");
        });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.filtedData,
          pageSize: 8,
          pageNumber:$this.pageNumber,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new videoRow({
                propsData: {
                  item,
                  index,
                  vm: $this
                }
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
          afterPageOnClick() {
            $this.pageNumber = $(".paginationjs-page.active").attr("data-num");
          },
        });
      });
    },
    async fileChange(e) {
      this.imageData.append("file", e.target.files[0]);
      let res = await axios.post("uploadVideoIntroImg", this.imageData);
      this.upLoadImg = `./upload/article/${res.data}`;
    },
    create() {
      this.postTitle = "";
      this.status = "publish";
      this.order = "0";
      this.postContent = "";
      this.ID = "";
      this.actionType = "insert";
      this.upLoadImg = "./images/default-image.png";
      this.videoLink = "";

      $(this.$refs.videoModal).modal("show");
    },
    edit(index) {
      this.postTitle = this.filtedData[index].post_title;
      this.status = this.filtedData[index].status;
      this.order = this.filtedData[index].order;
      this.postContent = this.filtedData[index].post_content;
      this.ID = this.filtedData[index].ID;
      this.upLoadImg = this.filtedData[index].media_link
        ? this.filtedData[index].media_link
        : "./images/default-image.png";
      this.actionType = "update";
      this.videoLink = this.filtedData[index].video_link;

      $(this.$refs.videoModal).modal("show");
    },
    delete(index) {
      axios
        .post("deleteKonwledge", {
          ID: this.filtedData[index].ID
        })
        .then(res => {
          this.message = `刪除成功`;
          this.getknowledgeVideoData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      axios
        .post("modifyKnowledge", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            post_title: this.postTitle,
            post_content: this.postContent,
            status: this.status,
            order: this.order,
            media_link: this.upLoadImg,
            video_link: this.videoLink,
            type: "video"
          }
        })
        .then(res => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getknowledgeVideoData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(
            `${
              this.actionType === "insert" ? "新增" : "更新"
            }發生錯誤，請稍後再試`
          );
        });
    },
    close() {
      $(this.$refs.videoModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    }
  }
};
</script>

<style lang="scss">
.bk-video-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .video-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .video-tabletitle {
      display: flex;
      overflow: auto;

      div {
        border-bottom: 2px solid #929292;
        text-align: center;

        &:not(:last-child) {
          border-right: 1px solid #bbbbbb;
        }
      }
    }

    .video-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .video-row {
        display: flex;

        .action-row {
          padding: 5px;
        }

        div {
          &:not(:first-child) {
            text-align: center;
          }

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .video-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .post-title {
      width: 50%;
      padding: 10px;
      text-align: start;
    }

    .category {
      width: 10%;
      padding: 10px;
    }

    .order {
      width: 5%;
      padding: 10px;
    }

    .status {
      width: 5%;
      padding: 10px;
    }

    .post_modified {
      width: 15%;
      padding: 10px;
    }

    .action-row {
      width: 15%;
      padding: 10px;
    }
  }

  .video-modal {
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