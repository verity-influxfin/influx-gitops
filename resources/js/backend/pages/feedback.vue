<template>
  <div class="bk-feedback-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">心得回饋</li>
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
          placeholder="名字"
          v-model="filter.name"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-user"></i>
          </span>
        </div>
      </div>
      <div class="input-group float-right" style="width: 300px; margin-right: 15px">
        <input
          type="text"
          class="form-control"
          placeholder="回饋內容"
          v-model="filter.feedback"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="feedback-block">
      <div class="feedback-tabletitle">
        <div class="name">填寫人姓名</div>
        <!-- <div class="user">填寫人ID</div> -->
        <div class="img">照片</div>
        <div class="video">影片</div>
        <div class="rank">身分</div>
        <div class="type">類別</div>
        <div class="date">填寫時間</div>
        <div class="message">回饋內容</div>
        <div class="status">是否公開</div>
        <div class="action-row">操作</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="feedback-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="feedback-modal modal fade"
      ref="feedbackModal"
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
                <span class="input-group-text">填寫人姓名</span>
              </div>
              <input
                type="text"
                class="form-control"
                placeholder="填寫人姓名"
                v-model="post_title"
              />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">大頭照</span>
              </div>

              <div style="display: grid">
                <img :src="imageSrc" class="img-fluid" style="width: 300px" />
                <input type="file" @change="uploadUserImg($event)" />
              </div>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">影片連結</span>
              </div>
              <input
                type="text"
                class="form-control"
                placeholder="填寫人姓名"
                v-model="video_link"
              />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">身分</span>
              </div>
              <select class="custom-select" v-model="rank">
                <option value="student">學生</option>
                <option value="officeWorker">上班族</option>
              </select>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">借款/投資</span>
              </div>
              <select class="custom-select" v-model="category">
                <option value="invest">投資人</option>
                <option value="loan">借款人</option>
              </select>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">填寫日期</span>
              </div>
              <v-date-picker v-model="date" :popover="{ visibility: 'click' }" />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">回饋內容</span>
              </div>
              <textarea
                type="text"
                class="form-control"
                style="height: 300px"
                v-model="feedback"
              />
            </div>

            <div class="input-group" style="width: 15%">
              <div class="input-group-prepend">
                <span class="input-group-text">是否公開</span>
              </div>
              <select class="custom-select" v-model="isActive">
                <option value="on">公開</option>
                <option value="off">不公開</option>
              </select>
            </div>
          </div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-secondary float-left" data-dismiss="modal">
              取消
            </button>
            <button class="btn btn-success float-right" @click="submit">送出</button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="img-modal modal fade"
      ref="imgModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn btn-close" data-dismiss="modal">
              <i class="far fa-times-circle"></i>
            </button>

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">回饋圖片</span>
              </div>
              <div style="display: grid">
                <img
                  :src="`/upload/feedbackImg/${image}`"
                  class="img-fluid"
                  style="width: 300px"
                />
                <input type="file" @change="uploadImg($event)" />
              </div>
            </div>
          </div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-secondary float-left" data-dismiss="modal">
              取消
            </button>
            <button class="btn btn-success float-right" @click="submitImg()">送出</button>
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
            <button class="btn btn-success float-right" @click="close">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";

let feedbackRow = Vue.extend({
  props: ["item", "vm"],
  components: {
    draggable,
  },
  data: () => ({
    isShow: false,
    cItem: {},
    feedbackImgs: [],
  }),
  template: `
    <li class="feedback-row">
      <div class="parent">
        <div class="name">{{item.post_title}}</div>
        <!-- <div class="user">{{item.userID}}</div> -->
        <div class="img"><img :src="item.imageSrc" class="img-fluid"/></div>
        <div class="video"><a v-if="item.video_link" :href="item.video_link" target="_blank"><i class="fas fa-external-link-alt"></i></a></div>
        <div class="rank">{{item.rank === 'student' ? '學生' : '上班族'}}</div>
        <div class="type">{{item.category === 'invest' ? '投資人' : '借款人'}}</div>
        <div class="date">{{item.post_modified}}</div>
        <div class="message">{{item.feedback}}</div>
        <div class="status">{{item.isActive ==='on' ? '是' : '否'}}</div>
        <div class="action-row">
          <button class="btn btn-warning btn-sm" style="margin-right:20px" v-if="item.isRead === '0'" @click="vm.read(item)">未讀</button>
          <button class="btn btn-primary btn-sm" style="margin-right:20px" @click="isShow = !isShow;showFile(item);">照片</button>
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
      </div>
      <div class="child" v-if="isShow">
        <div class="action-bar">
          <button class="btn btn-success btn-sm float-left" @click="vm.openImg(item)">
            <i class="fas fa-plus"></i>
            <span>新增</span>
          </button>
        </div>
          <draggable class="img-box" v-model="feedbackImgs" @change="changed($event,feedbackImgs)">
            <div class="img" v-for="(d,index) in feedbackImgs" :key="index">
              <img :src="'/upload/feedbackImg/'+d.image" class="img-fluid"/>
              <div class="action">
                <div class="edit"><i class="fas fa-edit" @click="vm.editImg(d)"></i></div>
                <div class="delete"><i class="fas fa-times" @click="vm.deleteImg(d)"></i></div>
              </div>
            </div>
          </draggable>
      </div>
    </li>
  `,
  watch: {
    "vm.date"() {
      if (this.isShow) {
        this.showFile(this.cItem);
      }
    },
  },
  methods: {
    async showFile(item) {
      this.cItem = item;

      if (this.isShow) {
        let res = await axios.post("bakGetFeedbackImg", {
          ID: this.cItem.ID,
        });

        this.feedbackImgs = res.data;
      }
    },
    changed($event, feedbackImgs) {
      let data = feedbackImgs.map((el, index) => {
        let item = {};
        item.order = index + 1;
        item.ID = el.ID;
        return item;
      });

      axios.post("bakUpdateImgOrder", { data });
    },
  },
});

export default {
  data: () => ({
    ID: "",
    post_title: "",
    userID: "",
    rank: "",
    category: "",
    post_modified: new Date(),
    date: new Date(),
    feedback: "",
    isActive: "",
    image: "",
    imageSrc: "",
    video_link: "",
    message: "",
    rawData: [],
    filtedData: [],
    filter: {
      name: "",
      feedback: "",
    },
    selectfeedbackData: {},
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getFeedbackData();
  },
  watch: {
    "filter.name"(newVal) {
      this.doFilter(this.filter.feedback, newVal);
    },
    "filter.feedback"(newVal) {
      this.doFilter(newVal, this.filter.name);
    },
  },
  methods: {
    doFilter(feedback, name) {
      this.filtedData = [];
      this.rawData.forEach((row, index) => {
        if (
          row.feedback.toLowerCase().indexOf(feedback.toLowerCase()) !== -1 &&
          row.post_title.toLowerCase().indexOf(name.toLowerCase()) !== -1
        ) {
          this.filtedData.push(row);
        }
      });
      this.pagination();
    },
    async getFeedbackData() {
      let res = await axios.get("getFeedbackData");

      this.rawData = res.data;
      this.filtedData = res.data;
      this.pagination();
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
              let component = new feedbackRow({
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
    async uploadUserImg($event) {
      let imageData = new FormData();
      imageData.append("file", $event.target.files[0]);
      let res = await axios.post("bakUploadUserImg", imageData);
      this.imageSrc = res.data;
    },
    create() {
      this.ID = "";
      this.post_title = "";
      this.video_link = "";
      this.imageSrc = "";
      this.rank = "";
      this.category = "";
      this.post_modified = new Date();
      this.feedback = "";
      this.isActive = "";
      this.actionType = "insert";

      $(this.$refs.feedbackModal).modal("show");
    },
    edit(item) {
      this.ID = item.ID;
      this.post_title = item.post_title;
      this.video_link = item.video_link;
      this.imageSrc = item.imageSrc;
      this.rank = item.rank;
      this.category = item.category;
      this.post_modified = new Date(item.post_modified);
      this.feedback = item.feedback;
      this.isActive = item.isActive;
      this.actionType = "update";

      $(this.$refs.feedbackModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteFeedbackData", {
          ID: item.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.getFeedbackData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    read(item) {
      axios
        .post("readFeedbackData", {
          ID: item.ID,
          data: {
            isRead: 1,
          },
        })
        .then((res) => {
          this.getFeedbackData();
        })
        .catch((error) => {
          alert(`發生錯誤，請稍後再試`);
        });
    },
    submit() {
      let d = new Date(this.date);
      let date_item = {
        year: d.getFullYear(),
        month: (d.getMonth() + 1 < 10 ? "0" : "") + (d.getMonth() + 1),
        day: (d.getDate() < 10 ? "0" : "") + d.getDate(),
      };

      axios
        .post("modifyFeedbackData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            ID: this.ID,
            post_title: this.post_title,
            video_link: this.video_link,
            imageSrc: this.imageSrc,
            category: this.category,
            post_modified: `${date_item.year}-${date_item.month}-${date_item.day}`,
            feedback: this.feedback,
            isActive: this.isActive,
          },
        })
        .then((res) => {
          this.message = `${this.actionType === "insert" ? "新增" : "更新"}成功`;

          this.getFeedbackData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`${this.actionType === "insert" ? "新增" : "更新"}發生錯誤，請稍後再試`);
        });
    },
    close() {
      $(this.$refs.feedbackModal).modal("hide");
      $(this.$refs.imgModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    },
    openImg(item) {
      this.actionType = "insert";
      this.selectfeedbackData = item;
      this.image = "";
      $(this.$refs.imgModal).modal("show");
    },
    editImg(data) {
      this.actionType = "update";
      this.selectfeedbackData = data;
      this.image = data.image;
      $(this.$refs.imgModal).modal("show");
    },
    deleteImg(data) {
      axios
        .post("bakDeleteFeedbackImg", {
          ID: data.ID,
        })
        .then((res) => {
          this.message = `刪除成功`;
          this.date = new Date();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    async uploadImg($event) {
      let imageData = new FormData();
      imageData.append("file", $event.target.files[0]);
      let res = await axios.post("bakUploadFeedbackImg", imageData);
      this.image = res.data;
    },
    submitImg() {
      axios
        .post("bakModifyFeedbackImgData", {
          actionType: this.actionType,
          ID: this.selectfeedbackData.ID,
          data: {
            image: this.image,
            feedbackID: this.selectfeedbackData.ID,
          },
        })
        .then((res) => {
          this.message = `${this.actionType === "insert" ? "新增" : "更新"}成功`;
          this.date = new Date();
          $(this.$refs.messageModal).modal("show");
        })
        .catch((error) => {
          alert(`${this.actionType === "insert" ? "新增" : "更新"}發生錯誤，請稍後再試`);
        });
    },
  },
};
</script>

<style lang="scss">
.bk-feedback-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .feedback-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .feedback-tabletitle {
      display: flex;
      overflow: auto;

      div {
        border-bottom: 2px solid #929292;
        text-align: center;
        padding: 5px;

        &:not(:last-child) {
          border-right: 1px solid #bbbbbb;
        }
      }
    }

    .feedback-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .feedback-row {
        .parent {
          display: flex;

          div {
            padding: 5px;
            text-align: center;

            &:not(:last-child) {
              border-right: 1px solid #bbbbbb;
            }
          }
        }

        .child {
          border-top: 1px solid;
          border-bottom: 2px solid;
          padding: 5px;

          .img-box {
            padding: 5px;
            overflow: auto;

            .img {
              margin: 5px;
              width: calc(20% - 10px);
              float: left;
              position: relative;

              .action {
                position: absolute;
                top: 0px;
                right: 0px;
                display: flex;
                background: #f0f8ff57;

                %font {
                  font-size: 20px;
                  margin: 5px;
                  padding: 0px 5px;
                  cursor: pointer;

                  &:hover {
                    filter: brightness(0.5);
                  }
                }

                .edit {
                  @extend %font;
                  color: #dadada;
                }

                .delete {
                  @extend %font;
                  color: #ff0f0f;
                }
              }
            }
          }
        }
      }

      .feedback-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    .name {
      width: 7%;
    }

    .user {
      width: 6%;
    }

    .img {
      width: 6%;
    }

    .video {
      width: 6%;
    }

    .type {
      width: 5%;
    }

    .rank {
      width: 5%;
    }

    .date {
      width: 12%;
    }

    .message {
      width: 39%;
    }

    .status {
      width: 6%;
    }

    .action-row {
      width: 15%;
    }
  }

  .feedback-modal {
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
