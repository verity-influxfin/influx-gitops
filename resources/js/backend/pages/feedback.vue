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
      <div
        class="input-group float-right"
        style="width: 300px; margin-right: 15px"
      >
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
        <div class="user">填寫人ID</div>
        <div class="rank">身分</div>
        <div class="type">類別</div>
        <div class="date">填寫時間</div>
        <div class="message">訊息內容</div>
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
                v-model="name"
              />
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">填寫人ID</span>
              </div>
              <input
                type="text"
                class="form-control"
                placeholder="填寫人ID"
                v-model="userID"
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
              <select class="custom-select" v-model="type">
                <option value="invest">投資端</option>
                <option value="loan">借款端</option>
              </select>
            </div>

            <div class="input-group" style="width: 95%">
              <div class="input-group-prepend">
                <span class="input-group-text">填寫日期</span>
              </div>
              <v-date-picker
                v-model="date"
                :popover="{ visibility: 'click' }"
              />
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
let feedbackRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="feedback-row">
        <div class="name">{{item.name}}</div>
        <div class="user">{{item.userID}}</div>
        <div class="rank">{{item.rank === 'student' ? '學生' : '上班族'}}</div>
        <div class="type">{{item.type === 'invest' ? '投資端' : '借款端'}}</div>
        <div class="date">{{item.date}}</div>
        <div class="message">{{item.feedback}}</div>
        <div class="status">{{item.isActive ==='on' ? '是' : '否'}}</div>
      <div class="action-row">
        <button class="btn btn-warning btn-sm" style="margin-right:20px" v-if="item.isRead === '0'" @click="vm.read(item)">已讀</button>
        <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
        <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
      </div>
    </li>
  `,
});

export default {
  data: () => ({
    ID: "",
    name: "",
    userID: "",
    rank: "",
    type: "",
    date: new Date(),
    feedback: "",
    isActive: "",
    message: "",
    rawData: [],
    filtedData: [],
    filter: {
      name: "",
      feedback: "",
    },
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
          row.name.toLowerCase().indexOf(name.toLowerCase()) !== -1
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
    create() {
      this.ID = "";
      this.name = "";
      this.userID = "";
      this.rank = "";
      this.type = "";
      this.date = new Date();
      this.feedback = "";
      this.isActive = "";
      this.actionType = "insert";

      $(this.$refs.feedbackModal).modal("show");
    },
    edit(item) {
      this.ID = item.ID;
      this.name = item.name;
      this.userID = item.userID;
      this.rank = item.rank;
      this.type = item.type;
      this.date = new Date(item.date);
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
            name: this.name,
            userID: this.userID,
            type: this.type,
            type: this.type,
            date: `${date_item.year}-${date_item.month}-${date_item.day}`,
            feedback: this.feedback,
            isActive: this.isActive,
          },
        })
        .then((res) => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getFeedbackData();
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
      $(this.$refs.feedbackModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
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
        display: flex;

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
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
      width: 45%;
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