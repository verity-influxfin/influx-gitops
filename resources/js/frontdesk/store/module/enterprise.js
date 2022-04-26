import Axios from 'axios'
const state = () => ({
    applyInfo: {},
    caseOverview: {
        title: '我的申貸',
        productTitle: '',
        caseNum: '',
        startDate: '',
        loanMoney: '',
        loanDur: ''
    },
    progressOverview: {
        title: '資料提供進度',
        principal: {
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
    },
    certifications: {
        idcard: {
            alias: 'idcard',
            description: '實名',
            user_status: null
        },
        email: {
            alias: 'email',
            description: '驗證常用E-Mail位址',
            user_status: null
        },
        profile: {
            alias: 'profile',
            description: '提供個人基本資料',
            user_status: null
        },
        passbookcashflow: {
            alias: 'passbookcashflow',
            description: '提供近6個月封面及內頁公司存摺',
            user_status: null
        },
        employeeinsurancelist: {
            alias: 'employeeinsurancelist',
            description: '員工投保人數資料',
            user_status: null
        },
        profilejudicial: {
            alias: 'profilejudicial',
            description: '提供公司資料表',
            user_status: null
        },
        incomestatement: {
            alias: 'incomestatement',
            description: '提供公司資料表',
            user_status: null
        },
        investigationjudicial: {
            alias: 'investigationjudicial',
            description: '公司聯徵報告',
            user_status: null
        },
        profile: {
            alias: 'profile',
            description: '個人基本資料',
            user_status: null
        },
        simplificationfinancial: {
            alias: 'simplificationfinancial',
            description: '存摺',
            user_status: null
        },
        simplificationjob: {
            alias: 'simplificationjob',
            description: '個人所得',
            user_status: null
        },
        investigationa11: {
            alias: 'investigationa11',
            description: '聯合徵信報告+A11',
            user_status: null
        },
        businesstax: {
            alias: 'businesstax',
            description: '存摺',
            user_status: null
        },
        governmentauthorities: {
            alias: 'governmentauthorities',
            description: '(變更事項)設立登記表',
            user_status: null
        },
        judicialguarantee: {
            alias: 'judicialguarantee',
            description: '公司授權核實',
            user_status: null
        },
    }
})

const mutations = {
    mutateCaseOverview(state, data) {
        const startDate = new Date(data.created_at * 1000).toLocaleString().split(' ')[0]
        state.applyInfo = data
        state.caseOverview = {
            title: '我的申貸',
            productTitle: data.product_name,
            caseNum: data.target_no,
            startDate,
            loanMoney: data.amount.toLocaleString(),
            loanDur: data.instalment + '個月'
        }
        data.certification.forEach(x => {
            state.certifications[x.alias] = x
        })
        let progess = state.progressOverview
        const principalProcess = (data) => {
            let times = 0
            times = data.idcard.user_status === null ? times : times + 1
            times = data.email.user_status === null ? times : times + 1
            times = data.profile.user_status === null ? times : times + 1
            times = data.simplificationfinancial.user_status === null ? times : times + 1
            times = data.simplificationjob.user_status === null ? times : times + 1
            times = data.investigationa11.user_status === null ? times : times + 1
            return times
        }
        const companyProcess = (data) => {
            let times = 0
            times = data.governmentauthorities.user_status === null ? times : times + 1
            times = data.judicialguarantee.user_status === null ? times : times + 1
            times = data.profilejudicial.user_status === null ? times : times + 1
            times = data.passbookcashflow.user_status === null ? times : times + 1
            times = data.businesstax.user_status === null ? times : times + 1
            times = data.employeeinsurancelist.user_status === null ? times : times + 1
            times = data.incomestatement.user_status === null ? times : times + 1
            times = data.investigationjudicial.user_status === null ? times : times + 1
            return times
        }
        progess.principal.process.now = principalProcess(state.certifications)
        progess.principal.process.percentage = String(Math.floor(progess.principal.process.now / state.progressOverview.principal.process.all) * 100) + '%'
        progess.company.process.now = companyProcess(state.certifications)
        progess.company.process.percentage = String(Math.floor(progess.company.process.now / progess.company.process.all) * 100) + '%'
        state.progressOverview = progess
    },
}
const actions = {
    updateCaseOverview({ commit }, payload) {
        return Axios.get('/api/v1/product/applyinfo?id=' + payload.id).then(({ data }) => {
            commit('mutateCaseOverview', data.data);
        })
    }
}

export default {
    namespaced: true,
    state,
    // getters,
    actions,
    mutations
}
