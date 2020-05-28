const applyingCodeList = [0, 1, 2, 3, 4, 20, 21, 22, 23, 24, 25];
const installmentCodeList = [5];
const doneCodeList = [8, 9, 10];

export default {
    mutationExperiencesData(state, data) {
        state.experiences = data;
    },
    mutationKnowledgeData(state, data) {
        $.each(data, (index, row) => {
            data[index].link = `/articlepage/knowledge-${row.id}`;
        });

        state.knowledge = data;
    },
    mutationSharesData(state, data) {
        $.each(data, (index, row) => {
            data[index].link = `/vlog/${row.category}`;
        });

        state.shares = data;
    },
    mutationInterviewData(state, data) {
        state.interview = data;
    },
    mutationNewsData(state, data) {
        $.each(data, (index, row) => {
            data[index].link = `/articlepage/news-${row.id}`;
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