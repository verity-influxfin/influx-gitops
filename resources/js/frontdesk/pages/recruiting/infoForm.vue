<template>
  <div class="recruit-info-form">
    <div class="text-center mb-3">
      <img src="@/asset/images/logo_puhey.png" style="width: 142px" />
    </div>
    <form class="form-content" onsubmit="return false">
      <div class="form-title">應徵人員履歷資料表</div>
      <div class="row no-gutters">
        <div class="col-6">
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
          <div class="form-group row">
            <label class="col-form-label">*姓名：</label>
            <div class="col">
              <input type="text" class="form-control" v-model="formData.name" />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">血型：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.bloodType"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">身高：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.height"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">體重：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.weight"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">出生年月日：</label>
            <div class="col">
              <input
                type="date"
                class="form-control"
                v-model="formData.birthDate"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">婚姻狀況：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.maritalStatus"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">身份證字號：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.idNumber"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label">興趣、嗜好：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.hobbies"
              />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="portrait-upload">
            <div>
              <input
                type="file"
                ref="fileUpload"
                @change="onChooseFile"
                hidden
                accept="image/*"
              />
            </div>
            <div class="no-file" v-if="!portraitFile">PHOTO</div>
            <div class="v-else">
              <img
                :src="portraitPreview"
                class="mb-4"
                style="max-height: 300px"
              />
            </div>
            <div class="row no-gutters justify-content-center">
              <div class="col-auto mr-2">
                <button class="btn btn-upload-primary" @click="onClickFile">
                  上傳檔案
                </button>
              </div>
              <div class="col-auto">
                <button class="btn btn-upload-secondary" @click="onClickFile">
                  重新修改
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label">*戶籍地址：</label>
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="formData.registeredAddress"
          />
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label">*通訊地址：</label>
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="formData.mailingAddress"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="form-group row">
            <label class="col-form-label">住家電話：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.homePhone"
              />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group row">
            <label class="col-form-label">行動電話：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.mobilePhone"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="form-group row">
            <label class="col-form-label">電子信箱：</label>
            <div class="col">
              <input
                type="email"
                class="form-control"
                v-model="formData.email"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="divider"></div>
      <div class="form-group row">
        <label class="col-form-label">最高學歷：</label>
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="formData.highestEducation"
          />
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label">專長：</label>
        <div class="col">
          <input
            type="text"
            class="form-control"
            v-model="formData.expertise"
          />
        </div>
      </div>
      <div class="divider"></div>
      <div class="form-subtitle">工作經歷：</div>
      <div class="row" v-for="(item,index) in formData.workExperiences" :key="index">
        <div class="col-4">
          <div class="form-group row">
            <label class="col-form-label">公司名稱：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="item.companyName"
              />
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="form-group row">
            <label class="col-form-label">工作內容：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="item.jobDescription"
              />
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="form-group row">
            <label class="col-form-label">經歷年資：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="item.yearsOfExperience"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="divider"></div>
      <div class="row">
        <div class="col-6">
          <div class="form-group row">
            <label class="col-form-label">填 表 人：</label>
            <div class="col">
              <input
                type="text"
                class="form-control"
                v-model="formData.submitterName"
              />
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group row">
            <label class="col-form-label">日期：</label>
            <div class="col">
              <input
                type="date"
                class="form-control"
                v-model="formData.submissionDate"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <button class="btn btn-next" @click="onClickNextStep()">下一步</button>
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
        bloodType: '', // 血型
        height: '', // 身高
        weight: '', // 體重
        birthDate: '', // 出生年月日
        maritalStatus: '', // 婚姻狀況
        idNumber: '', // 身份證字號
        hobbies: '', // 興趣、嗜好
        registeredAddress: '', // 戶籍地址
        mailingAddress: '', // 通訊地址
        homePhone: '', // 住家電話
        mobilePhone: '', // 行動電話
        email: '', // 電子信箱
        highestEducation: '', // 最高學歷
        expertise: '', // 專長
        workExperiences: [
          {
            companyName: '', // 公司名稱
            jobDescription: '', // 工作內容
            yearsOfExperience: null, // 經歷年資
          },
          {
            companyName: '', // 公司名稱
            jobDescription: '', // 工作內容
            yearsOfExperience: null, // 經歷年資
          },
          {
            companyName: '', // 公司名稱
            jobDescription: '', // 工作內容
            yearsOfExperience: null, // 經歷年資
          },
        ],
        submitterName:'',
        submissionDate: '',
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
    onClickNextStep() {
      let formData = new FormData()
      formData.append('image', this.portraitFile)
      formData.append('data', JSON.stringify(this.formData))

      axios.post('/uploadJobApplication', formData).then((res) => {
        if (res.data == 'Success') {
          this.$router.push('/recruiting/qa-form');
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
    margin-bottom: 25px;
    border-bottom: 1.5px solid #f3f3f3;
    letter-spacing: 0.2em;
    color: #036eb7;
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
  }
  .divider {
    margin: 14px 0;
    width: 100%;
    border-bottom: 1.5px solid #f3f3f3;
  }
  .form-subtitle {
    @extend .col-form-label;
    text-align: left;
    color: #036eb7;
  }
  .btn-next{
    padding: 4px 22px;
    font-size: 14px;
    line-height: 20px;
    color: #ffffff;
    background: rgba(112, 112, 112, 0.5);;
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
</style>
