const state = () => ({
    caseOverview: {
        title: '我的申貸',
        productTitle: '信保融資專案',
        caseNum: 'FFF1233123',
        startDate: '2022/01/02',
        loanMoney: '123,123',
        loanDur:'36個月'
    },
    progressOverview: {
        title: '資料提供進度',
        principal:{
            title: '負責人資料更新',
            process: {
                now: 1,
                all: 6,
                percentage: '16%'
            }
        },
        company: {
            title: '公司資料更新',
            process: {
                now: 4,
                all: 8,
                percentage: '50%'
            }
        },
        guarantor: {
            title: '保證人/配偶資料更新',
            process: {
                now: 0,
                all: 4,
                percentage: '0%'
            }
        }
    }
})

const mutations = {
    mutateCaseTitle(state, { title }) {
        state.caseOverview.title = title
    }
}

export default {
    namespaced: true,
    state,
    // getters,
    // actions,
    mutations
}
