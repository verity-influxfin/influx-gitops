const applyingCodeList = [0, 1, 2, 3, 4, 20, 21, 22, 23, 24, 25];
const installmentCodeList = [5];
const doneCodeList = [8, 9, 10];

export default {
    mutationExperiencesData(state, data) {
        state.experiences = data;
    },
    mutationKnowledgeData(state, data) {
        $.each(data, (index, row) => {
            data[index].link = `/articlepage?q=knowledge-${row.ID}`;
        });
        
        state.knowledge = data;
    },
    mutationVideoData(state, data) {
        $.each(data, (index, row) => {
            if(row.category){
                data[index].link = `/vlog?q=${row.category}`;
            }else{
                data[index].link = `/videopage?q=${row.ID}&category=share`;
            }
        });

        state.video = data;
    },
    mutationInterviewData(state, data) {
        state.interview = data;
    },
    mutationNewsData(state, data) {
        $.each(data, (index, row) => {
            data[index].link = `/articlepage?q=news-${index}`;
        });

        state.news = data;
    },
    mutationUserData(state, data) {
        state.userData = data;
    },
    mutationRepaymentData(state, data) {
        let applying = [];
        let installment = [];
        let done = [];

        data.data.list.forEach((row, index) => {
            if (applyingCodeList.indexOf(row.status) !== -1 && row.sub_status !== 8) {
                applying.push(row);
            } else if (installmentCodeList.indexOf(row.status) !== -1) {
                installment.push(row);
                if (1 === row.sub_status) {
                    applying.push(row);
                }
            } else if (doneCodeList.indexOf(row.status) !== -1) {
                done.push(row);
            }
        });
        applying = applying.reverse();
        installment = installment.reverse();
        done = done.reverse();

        state.applyList = { applying, installment, done };
    },
    mutationInvestmentData(state,data){
        state.investAccountData = data.data;
    }
}