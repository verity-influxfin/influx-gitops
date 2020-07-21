<template>
  <div class="milestone-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">里程碑</li>
      </ol>
    </nav>

    <div class="milestone-card">
      <div class="action-bar">
        <button class="btn btn-primary float-left" @click="create()">
          <i class="fas fa-plus"></i>
          <span>新增</span>
        </button>

        <div class="input-group float-right" style="width: 300px;">
          <input type="text" class="form-control" placeholder="里程碑標題" v-model="filter.title" />
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="milestone-list">
        <div class="milestone-tabletitle">
          <div class="title">標題</div>
          <div class="hook-date">錨點日期</div>
          <div class="content">內容</div>
          <div class="action-row">操作</div>
        </div>
        <div class="empty" v-if="rawData.length === 0">查無資料！</div>
        <div v-else>
          <ul class="milestone-container" ref="container"></ul>
          <div class="pagination" ref="pagination"></div>
        </div>
      </div>
    </div>

    <div
      class="milestone-modal modal fade"
      ref="milestoneModal"
      tabindex="-1"
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
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">標題</span>
              </div>
              <input type="text" class="form-control" placeholder="標題" v-model="title" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">錨點日期</span>
              </div>
              <v-date-picker v-model="hookDate" :popover="{ visibility: 'click' }" />
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">內容</span>
              </div>
              <textarea type="text" class="form-control" placeholder="內容" v-model="content" />
            </div>
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
let phoneRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="milestone-row">
        <div class="title">{{item.title}}</div>
        <div class="hook-date">{{item.hook_date}}</div>
        <div class="content">{{item.content}}</div>
        <div class="action-row">
          <button class="btn btn-info btn-sm" style="margin-right:20px" @click="vm.edit(item)">修改</button>
          <button class="btn btn-danger btn-sm" @click="vm.delete(item)">刪除</button>
        </div>
    </li>
  `
});

export default {
  data: () => ({
    ID: "",
    title: "",
    hookDate: new Date(),
    content: "",
    message: "",
    rawData: [],
    filter: {
      title: ""
    }
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getMilestoneData();
  },
  methods: {
    getMilestoneData() {
      axios
        .post("getMilestoneData")
        .then(res => {
          this.rawData = res.data;
          this.pagination();
        })
        .catch(error => {
          console.log("getMilestone 發生錯誤，請稍後再試");
        });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.rawData,
          pageSize: 8,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new phoneRow({
                propsData: {
                  item,
                  vm: $this
                }
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          }
        });
      });
    },
    create() {
      this.title = "";
      this.hookDate = new Date();
      this.content = "";
      this.ID = "";
      this.actionType = "insert";

      $(this.$refs.milestoneModal).modal("show");
    },
    edit(item) {
      this.title = item.title;
      this.hookDate = new Date(item.hook_date);
      this.content = item.content;
      this.ID = item.ID;
      this.actionType = "update";

      $(this.$refs.milestoneModal).modal("show");
    },
    delete(item) {
      axios
        .post("deleteMilestoneData", {
          ID: item.ID
        })
        .then(res => {
          this.message = `刪除成功`;
          this.getMilestoneData();
          $(this.$refs.messageModal).modal("show");
        })
        .catch(error => {
          alert(`刪除發生錯誤，請稍後再試`);
        });
    },
    submit() {
      let d = new Date(this.hookDate);
      let date_item = {
        year: d.getFullYear(),
        month: (d.getMonth() + 1 < 10 ? "0" : "") + (d.getMonth() + 1),
        day: (d.getDate() < 10 ? "0" : "") + d.getDate()
      };

      axios
        .post("modifyMilestoneData", {
          actionType: this.actionType,
          ID: this.ID,
          data: {
            title: this.title,
            hook_date: `${date_item.year}-${date_item.month}-${date_item.day}`,
            content: this.content
          }
        })
        .then(res => {
          this.message = `${
            this.actionType === "insert" ? "新增" : "更新"
          }成功`;

          this.getMilestoneData();
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
      $(this.$refs.milestoneModal).modal("hide");
      $(this.$refs.messageModal).modal("hide");
    }
  }
};
</script>

<style lang="scss">
.milestone-wrapper {
  overflow: hidden;
  padding: 10px;

  .milestone-card {
    margin: 15px 5px;
    padding: 10px;
    background: #f5f4ff;
    border-radius: 10px;
    box-shadow: 0 0 4px black;

    .empty {
      padding: 10px;
      text-align: center;
    }

    .action-bar {
      position: relative;
      overflow: auto;
    }

    .milestone-list {
      margin: 15px 0px;
      padding: 10px;
      box-shadow: 0 0 2px black;

      .milestone-tabletitle {
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

      .milestone-container {
        margin-bottom: 20px;
        padding: 0px;
        list-style: none;

        .milestone-row {
          display: flex;

          .action-row {
            padding: 5px;
          }

          div {
            &:not(:last-child) {
              border-right: 1px solid #bbbbbb;
            }
          }
        }

        .milestone-row:not(:last-child) {
          border-bottom: 1px solid #b1b1b1;
        }
      }

      .title {
        width: 20%;
        padding: 10px;
      }

      .hook-date {
        width: 10%;
        padding: 10px;
        text-align: center;
      }

      .content {
        width: 75%;
        padding: 10px;
      }

      .action-row {
        width: 15%;
        padding: 10px;
        text-align: center;
      }
    }
  }

  .milestone-modal {
    @media (min-width: 576px) {
      .modal-dialog {
        max-width: 70%;
        margin: 1.75rem auto;
      }
    }

    .input-group {
      margin-bottom: 10px;
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;
  }

  .btn-close {
    float: right;
    font-size: 27px;
    z-index: 1;
  }
}
</style>