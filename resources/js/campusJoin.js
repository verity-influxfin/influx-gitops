$(() => {
    if (new Date().getDate() >= new Date('2021/01/31 00:00:00').getDate()) {
        alert('報名已截止，詳情請見官網');
        location.replace('/campuspartner');
        return;
    }

    const vue = new Vue({
        el: '#campusJoin_wrapper',
        data: () => ({
            isSingup: false,
            teamName: "",
            memberList: []
        }),
        created() {
        },
        methods: {
            checked($evt) {
                if (!$($evt.target).val()) {
                    $($evt.target).addClass("empty").attr('placeholder', '請填寫這個欄位')
                } else {
                    $($evt.target).removeClass("empty").attr('placeholder', '');
                }
            },
            uploadFile($evt, index) {
                let upploadFile = new FormData();
                upploadFile.append("file", $evt.target.files[0]);
                upploadFile.append("fileType", $evt.target.name);

                axios
                    .post("/campusUploadFile", upploadFile)
                    .then((res) => {
                        this.memberList[index][$evt.target.name] = res.data;
                        alert("上傳成功！");
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;
                        $($evt.target).val("");
                        alert(errorsData);
                    });
            },
            createMember() {
                if (this.memberList.length < 3) {
                    this.memberList.push({
                        name: "",
                        school: "",
                        department: "",
                        grade: "",
                        mobile: "",
                        email: "",
                        selfIntro: "",
                        resume: "",
                        proposal: "",
                        portfolio: "",
                    });
                }
            },
            deleteRow(index) {
                if (confirm("確定移除此隊員?")) {
                    this.memberList.splice(index, 1);
                    alert("移除成功");
                }
            },
            submit() {
                let { memberList, teamName } = this;

                if ($('.empty').length !== 0) {
                    alert('有欄位未輸入');
                    return;
                }

                axios
                    .post("/campusSignup", { memberList, teamName })
                    .then((res) => {
                        this.isSingup = true;
                    })
                    .catch((error) => {
                        let errorsData = error.response.data;
                        Object.keys(errorsData.errors).forEach((key) => {
                            if ('index' in errorsData) {
                                $(`#${key}_${errorsData.index}`).attr('placeholder', errorsData.errors[key]).addClass("empty")
                            } else {
                                $(`#${key}`).attr('placeholder', errorsData.errors[key]).addClass("empty")
                            }
                        })
                    });
            }
        }
    });
})