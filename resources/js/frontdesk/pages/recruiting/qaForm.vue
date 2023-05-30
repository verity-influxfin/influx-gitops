<template>
  <div class="recruit-info-form">
    <div class="text-center mb-3">
      <img src="@/asset/images/logo_puhey.png" style="width: 142px" />
    </div>
    <form class="form-content" onsubmit="return false">
      <div class="form-title">面試問題</div>
      <div class="form-subtitle">
        歡迎參加普匯金融科技線上面試，請完成以下問答加速面試了解
      </div>
      <div class="divider"></div>
      <div class="row no-gutters">
        <div class="col-12 col-md-6">
          <div class="form-group row">
            <label class="col-form-label">*姓名：</label>
            <div class="col">
              <input type="text" class="form-control" v-model="formData.name" />
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group row">
            <label class="col-form-label">*年齡：</label>
            <div class="col">
              <input type="text" class="form-control" v-model="formData.age" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label">*應徵職位：</label>
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="formData.appliedPosition"
          />
        </div>
      </div>
      <div class="divider"></div>
      <div class="form-group" v-for="item in formData.questions" :key="item.q">
        <label class="form-label">{{ item.q }}</label>
        <div class="">
          <textarea
            class="form-control form-textarea"
            rows="5"
            v-model="item.a"
          ></textarea>
        </div>
      </div>
      <div class="text-center">
        <button class="btn btn-next" @click="onSubmit()">送出</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formData: {
        appliedPosition: '', // 應徵職位
        name: '', // 姓名
        age: '', // 年齡
        questions: [
          {
            q: 'Q1：曾經經歷最失敗的工作經驗？您如何克服？',
            a: ''
          },
          {
            q: 'Q2：為何離職(想離職)？',
            a: ''
          },
          {
            q: 'Q3：普匯金融科技與傳統金融最大差異有？',
            a: ''
          },
          {
            q: 'Q4：就本公司的這項職缺, 您覺得最大的難度與挑戰是甚麼？',
            a: ''
          },
          {
            q: 'Q5：想要加入普匯的原因是？',
            a: ''
          },
          {
            q: 'Q6：普匯小學堂中最有印象的一篇文章？',
            a: ''
          },
          {
            q: 'Q7：請簡介影響您最深的一本書或一個人, 為什麼？',
            a: ''
          },
          {
            q: 'Q8：影響您選擇工作的三大因素是？',
            a: ''
          },
        ]
      },
      portraitFile: null,
      portraitPreview: null,
    }
  },
  methods: {
    onClickFile() {
      this.$refs.fileUpload.click()
    },
    onChooseFile() {
      this.portraitFile = null
      this.portraitPreview = null
      const currentFiles = this.$refs.fileUpload.files
      if (currentFiles.length === 0) {
        return
      }
      this.portraitFile = currentFiles[0]
      const reader = new FileReader()
      reader.onload = (event) => {
        this.portraitPreview = event.target.result
      }
      reader.readAsDataURL(this.portraitFile)
    },
    onSubmit() {
      if (this.formData.appliedPosition == '' || this.formData.name == '' || this.formData.age == '') {
        alert('必填欄位 : 姓名、年齡和應徵職位')
        return
      }
      axios.post('/uploadGoogleQA', this.formData).then((res) => {
        console.log(res.data);
        if (res.data == 'Success') {
          alert('填寫成功');
          this.$router.push('/recruiting');
        }
      }).catch((error) => {
        console.log(error)
      })
    }
  },
}
</script>

<style lang="scss" scoped>
.recruit-info-form {
  max-width: 1300px;
  margin: 20px auto;
  padding: 24px 60px;
  background-image: url('~images/recruitingForm/form-bg-wave.png');
  background-size: cover;
  background-position: top center;
  min-height: 100vh;
  box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1),
    0 8px 10px -6px rgb(0 0 0 / 0.1);
  .form-content {
    padding: 36px 56px;
    background-color: #fff;
    border-radius: 20px;
  }
  .form-title {
    font-style: normal;
    text-align: center;
    font-weight: 500;
    font-size: 24px;
    line-height: 1.4;
    padding-bottom: 16px;
    letter-spacing: 0.2em;
    color: #036eb7;
  }
  .form-subtitle {
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.4;
    text-align: center;
    color: #393939;
  }
  .col-form-label {
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.4;
    text-align: right;
    width: 125px;
    letter-spacing: 0.24em;
    color: #393939;
  }
  .form-control {
    font-size: 16px;
    height: 28px;
    &.form-textarea {
      height: auto;
    }
  }
  .divider {
    margin: 14px 0;
    width: 100%;
    border-bottom: 1.5px solid #f3f3f3;
  }
  .form-label {
    @extend .col-form-label;
    text-align: left;
    width: auto;
    color: #036eb7;
  }
  .btn-next {
    padding: 4px 22px;
    font-size: 14px;
    line-height: 20px;
    color: #ffffff;
    background: #036eb7;
    border-radius: 6px;
  }
}

.portrait-upload {
  margin: 20px auto 0 45px;
  width: 330px;
  height: 395px;
  padding: 12px 8px;
  border: 1.5px solid #ced4da;
  border-radius: 6px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  .no-file {
    font-style: normal;
    text-align: center;
    font-weight: 500;
    font-size: 24px;
    line-height: 285px;
    letter-spacing: 0.2em;
    color: rgba(112, 112, 112, 0.5);
  }
  .btn-upload-primary {
    padding: 4px 22px;
    font-size: 14px;
    line-height: 20px;
    color: #ffffff;
    background: #036eb7;
    border-radius: 6px;
  }
  .btn-upload-secondary {
    padding: 4px 22px;
    font-size: 14px;
    line-height: 20px;
    color: #ffffff;
    background: #f29600;
    border-radius: 6px;
  }
}

@media only screen and (max-width: 768px) {
  .form-content {
    padding: 0 !important;
  }  
}
</style>
