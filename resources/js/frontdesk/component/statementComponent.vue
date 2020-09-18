<template>
  <div class="income-detail-card">
    <button
      type="button"
      class="down-csv btn btn-success btn-sm"
      @click="$emit('download',range);"
    >匯出CSV</button>
    <div class="input-group">
      <v-date-picker
        mode="range"
        v-model="range"
        class="date-picker"
        :popover="{ visibility: 'click' }"
        :key="new Date()"
      />
      <button class="btn btn-custom" type="button" @click="type='range';search()">
        <i class="fas fa-search"></i>
      </button>

      <label class="btn-rel" @click="search()">
        <input type="radio" name="radio" value="month" v-model="type" />
        <span>本月</span>
      </label>

      <label class="btn-rel" @click="search()">
        <input type="radio" name="radio" value="all" v-model="type" />
        <span>全部</span>
      </label>
    </div>
    <div class="no-passbook-table" v-if="passbook.length ===0">
      <div class="no-passbook">
        <img src="../asset/images/empty.svg" class="img-fluid" />
      </div>
    </div>
    <div class="passbook-table" v-else>
      <div class="s-title">
        <div class="remark">科目</div>
        <div class="amount">現金流量</div>
        <div class="bank_amount">虛擬帳戶餘額</div>
      </div>
      <div class="statement-cnt">
        <div class="statement-row" v-for="(item,index) in passbook" :key="index">
          <div v-for="(text,colIndex) in item" :class="colIndex" v-html="text" :key="colIndex"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["list"],
  data: () => ({
    type: "",
    range: {
      start: new Date(),
      end: new Date(),
    },
    passbook: [],
  }),
  watch: {
    "$props.list"(newData) {
      this.passbook = [];
      let date = new Date();
      if (this.type === "month") {
        this.range["start"] = new Date(date.getFullYear(), date.getMonth(), 1);
        this.range["end"] = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate()
        );
      }

      if (this.type === "all") {
        let startdate = new Date(
          parseInt(
            `${this.$props.list[this.$props.list.length - 1].created_at}000`
          )
        );
        this.range["start"] = new Date(
          startdate.getFullYear(),
          startdate.getMonth(),
          startdate.getDate()
        );
        this.range["end"] = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate()
        );
      }

      this.$props.list.forEach((row, index) => {
        if (
          this.range.start.getTime() <= row.created_at + "000" &&
          row.created_at + "000" <= this.range.end.getTime()
        ) {
          this.passbook.push({
            remark: `${row.remark}<br>${row.tx_datetime.substr(2)}`,
            amount: `<span style="color:${
              row.amount > 0 ? "#5192E5" : "#FF4758"
            }">${this.format(row.amount)}</span>`,
            bank_amount: this.format(row.bank_amount),
          });
        }
      });
    },
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    search() {
      this.$emit("searchDteail");
    },
  },
};
</script>

<style lang="scss">
.income-detail-card {
  .down-csv {
    float: right;
    margin-bottom: 5px;
  }

  .input-group {
    margin-bottom: 10px;

    .date-picker {
      width: 65%;
    }
  }

  .btn-rel {
    display: flex;
    margin: 0px 6px;

    input {
      margin: 12px 0px;
    }

    span {
      line-height: 38px;
    }
  }

  .btn-custom {
    border: 0;
    background: none;
    padding: 2px 5px;
    margin-top: 2px;
    margin-left: -28px;
    margin-bottom: 0;
    border-radius: 3px;
    color: #cacaca;
  }

  .passbook-table {
    height: 380px;
    overflow: hidden;
    position: relative;

    %flex {
      display: flex;

      .remark {
        width: 140px;
        padding: 4px;
      }

      .amount {
        width: 80px;
        padding: 4px;
      }

      .bank_amount {
        width: 120px;
        padding: 4px;
      }
    }

    .s-title {
      @extend %flex;
      text-align: center;
    }

    .statement-cnt {
      height: 348px;
      overflow: scroll;

      .statement-row {
        @extend %flex;

        &:not(:last-of-type) {
          border-bottom: 1px solid #cacaca;
        }

        .amount,
        .bank_amount {
          text-align: center;
          line-height: 56px;
        }
      }
    }
  }

  .no-passbook-table {
    height: 380px;
    overflow: hidden;
    position: relative;

    .no-passbook {
      width: 60%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.3;
    }
  }
}

@media screen and (max-width: 767px) {
  .income-detail-card {
    .input-group {
      .date-picker {
        width: 64%;
      }
    }

    .passbook-table {
      %flex {
        .remark {
          width: 170px;
        }

        .amount {
          width: 75px;
        }

        .bank_amount {
          width: 75px;
        }
      }
    }

    .passbook-table {
      .s-title {
        .remark,
        .amount {
          line-height: 48px;
        }
      }
    }

    .statement-cnt {
      .statement-row {
        .amount,
        .bank_amount {
          text-align: center;
          line-height: 48px;
        }
      }
    }
  }
}
</style>