<template>
  <div class="bk-campus-wrapper">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="index">
            <i class="fas fa-home"></i>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">普匯大使</li>
      </ol>
    </nav>

    <div class="action-bar">
      <div class="input-group float-right mg-r" style="width: 200px">
        <select class="form-control" v-model="fileType">
          <option value="resume">個人簡歷表</option>
          <option value="proposal">企劃書</option>
          <option value="portfolio">作品集</option>
        </select>
        <div class="input-group-append">
          <button class="btn btn-success" @click="downloadTypeFile()">
            <i class="fas fa-download"></i>
          </button>
        </div>
      </div>
      <div class="input-group float-right" style="width: 300px; margin-right: 15px">
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
          placeholder="隊伍名稱"
          v-model="filter.teamName"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fas fa-users-cog"></i>
          </span>
        </div>
      </div>
    </div>

    <div class="campus-block">
      <div class="campus-tabletitle">
        <div class="teamName">隊伍名稱</div>
        <div class="name">姓名</div>
        <div class="school">學校</div>
        <div class="department">科系</div>
        <div class="grade">年級</div>
        <div class="email">E-mail</div>
        <div class="mobile">手機</div>
        <div class="resume">個人簡歷表</div>
        <div class="proposal">企劃書</div>
        <div class="portfolio">作品集</div>
        <div class="selfIntro">自我介紹</div>
      </div>
      <div class="empty" v-if="filtedData.length === 0">查無資料！</div>
      <div v-else>
        <ul class="campus-container" ref="container"></ul>
        <div class="pagination" ref="pagination"></div>
      </div>
    </div>

    <div
      class="messageModal-modal modal fade"
      ref="messageModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">{{ selfIntro }}</div>
          <div class="modal-footer" style="display: block">
            <button class="btn btn-success float-right" data-dismiss="modal">確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let campusRow = Vue.extend({
  props: ["item", "vm"],
  template: `
    <li class="campus-row">
        <div class="teamName">{{item.teamName}}</div>
        <div class="name">{{item.name}}</div>
        <div class="school">{{item.school}}</div>
        <div class="department">{{item.department}}</div>
        <div class="grade">{{item.grade}}</div>
        <div class="email">{{item.email}}</div>
        <div class="mobile">{{item.mobile}}</div>
        <div class="resume"><i class="fas fa-file-download text-success" v-if="item.resume" @click="download(item.resume,'resume')"></i></div>
        <div class="portfolio"><i class="fas fa-file-download text-success" v-if="item.portfolio" @click="download(item.portfolio,'portfolio')"></i></div>
        <div class="proposal"><i class="fas fa-file-download text-success" v-if="item.proposal" @click="download(item.proposal,'proposal')"></i></div>
        <div class="selfIntro" ><i class="fas fa-align-justify text-info" @click="showContent(item.selfIntro)"></i></div>
    </li>
  `,
  methods: {
    showContent(selfIntro) {
      this.vm.selfIntro = selfIntro;
      $(this.vm.$refs.messageModal).modal("show");
    },
    download(file, type) {
      $("#fileDownloadIframe").remove();
      $("body").append(
        `<iframe id="fileDownloadIframe" src="/getMemberFile?file=${file}&type=${type}" style="display: none"></iframe>`
      );
    },
  },
});

export default {
  data: () => ({
    pageNumber:1,
    fileType: "resume",
    selfIntro: "",
    rawData: [],
    filtedData: [],
    filter: {
      name: "",
      teamName: "",
    },
  }),
  created() {
    $("title").text(`後臺系統 - inFlux普匯金融科技`);
    this.getCampusData();
  },

  watch: {
    "filter.name"(newVal) {
      this.doFilter(this.filter.teamName, newVal);
    },
    "filter.teamName"(newVal) {
      this.doFilter(newVal, this.filter.name);
    },
  },
  methods: {
    doFilter(teamName, name) {
      this.filtedData = [];
      this.rawData.forEach((row, index) => {
        if (
          row.teamName.toLowerCase().indexOf(teamName.toLowerCase()) !== -1 &&
          row.name.toLowerCase().indexOf(name.toLowerCase()) !== -1
        ) {
          this.filtedData.push(row);
        }
      });
      this.pagination();
    },
    async getCampusData() {
      let res = await axios.get("bakGetCampusData");

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
          pageNumber:this.pageNumber,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new campusRow({
                propsData: {
                  item,
                  vm: $this,
                },
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
          afterPageOnClick() {
            this.pageNumber = $(".paginationjs-page.active").attr("data-num");
          },
        });
      });
    },
    downloadAllFile() {
      console.log(this.flieType);
    },
    downloadTypeFile() {
      $("#downloadTypeFile").remove();
      $("body").append(
        `<iframe id="downloadTypeFile" src="/bakDownloadTypeFile?fileType=${this.fileType}" style="display: none"></iframe>`
      );
    },
  },
};
</script>

<style lang="scss">
.bk-campus-wrapper {
  padding: 10px;

  .empty {
    padding: 10px;
    text-align: center;
  }

  .action-bar {
    position: relative;
    overflow: auto;
  }

  .campus-block {
    margin: 15px 0px;
    padding: 10px;
    box-shadow: 0 0 2px black;

    .campus-tabletitle {
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

    .campus-container {
      margin-bottom: 20px;
      padding: 0px;
      list-style: none;

      .campus-row {
        display: flex;

        div {
          padding: 5px;
          text-align: center;

          &:not(:last-child) {
            border-right: 1px solid #bbbbbb;
          }
        }
      }

      .campus-row:not(:last-child) {
        border-bottom: 1px solid #b1b1b1;
      }
    }

    %i {
      font-size: 20px;
      cursor: pointer;
    }

    .teamName {
      width: 11%;
    }
    .name {
      width: 7%;
    }
    .school {
      width: 13%;
    }
    .department {
      width: 12%;
    }
    .grade {
      width: 5%;
    }
    .email {
      width: 20%;
    }
    .mobile {
      width: 8%;
    }
    .resume {
      width: 7%;

      i {
        @extend %i;
      }
    }
    .proposal {
      width: 5%;
      i {
        @extend %i;
      }
    }
    .portfolio {
      width: 5%;
      i {
        @extend %i;
      }
    }
    .selfIntro {
      width: 7%;
      i {
        @extend %i;
      }
    }
  }

  .campus-modal {
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
