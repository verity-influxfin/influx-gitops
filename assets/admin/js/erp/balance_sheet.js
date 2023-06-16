var app = new Vue({
    el: '#page-wrapper',
    data: {
        searchform: {
            start_date: moment().subtract(1,'days').format('YYYY-MM-DD'),
            end_date: moment().format('YYYY-MM-DD'),
            user_id_int: '',
        },
        is_waiting_response: false,
        column: [],
        tab: 'b_dict',
        dictData: {
            user_id_int: null,
            end_date: '',
            left: {
                bank_balance: 0,
                virtual_account_balance: 0,
                principal_balance: 0,
                delay_principal_balance: 0,
                transfer_in_loss: 0,
                transfer_out_loss: 0,
                platform_fee: 0,
                transfer_out_fee: 0,
                donation_expense: 0,
                bank_verify_fee: 0,
                legal_collection_platform_fee: 0,
                total: 0,
            },
            right: {
                sales_tax: 0,
                interest_income: 0,
                transfer_in_gain: 0,
                prepayment_compensation: 0,
                delay_interest_income: 0,
                transfer_out_gain: 0,
                total: 0,
            },
            balance: 0,
            all_principal_repaid: 0,
            all_loan_amount: 0,
            all_interest_receivable: 0,
            all_delay_interest_receivable: 0,
        },
        diffData: {
            user_id_int: null,
            end_date: {
                before: '',
                after: '',
                diff: 0,
            },
            left: {
                bank_balance: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                virtual_account_balance: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                principal_balance: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                delay_principal_balance: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                transfer_in_loss: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                transfer_out_loss: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                platform_fee: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                transfer_out_fee: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                donation_expense: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                bank_verify_fee: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                legal_collection_platform_fee: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                total: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
            },
            right: {
                sales_tax: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                interest_income: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                transfer_in_gain: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                prepayment_compensation: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                delay_interest_income: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                transfer_out_gain: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
                total: {
                    before: 0,
                    after: 0,
                    diff: 0,
                },
            },
            balance: {
                before: 0,
                after: 0,
                diff: 0,
            },
            all_principal_repaid: {
                before: 0,
                after: 0,
                diff: 0,
            },
            all_loan_amount: {
                before: 0,
                after: 0,
                diff: 0,
            },
            all_interest_receivable: {
                before: 0,
                after: 0,
                diff: 0,
            },
            all_delay_interest_receivable: {
                before: 0,
                after: 0,
                diff: 0,
            },
        },
        lang: 'tw',
    },
    computed: {
        langKey() {
            if (this.lang === 'tw') {
                return {
                    left: {
                        bank_balance: '銀行餘額',
                        virtual_account_balance: '虛擬帳號餘額',
                        principal_balance: '本金餘額',
                        delay_principal_balance: '逾期本金餘額',
                        transfer_in_loss: '債權受讓:差額損失',
                        transfer_out_loss: '債權出讓:差額損失',
                        platform_fee: '平台手續費',
                        transfer_out_fee: '債權轉讓手續費',
                        donation_expense: '捐款費用',
                        bank_verify_fee: '金融驗證費',
                        legal_collection_platform_fee: '法催平台手續費',
                        total: '小計',
                        other: '其他損失',
                    },
                    right: {
                        sales_tax: '銷項稅額',
                        interest_income: '利息收入',
                        transfer_in_gain: '債權受讓:差額收益',
                        prepayment_compensation: '提還補償金',
                        delay_interest_income: '延滯息收入',
                        transfer_out_gain: '債權出讓:差額收益',
                        total: '小計',
                        other: '其他收入',
                    },
                    balance: '左右差額',
                    all_principal_repaid: '總已還本金',
                    all_loan_amount: '總出借金額',
                    all_interest_receivable: '總應收利息',
                    all_delay_interest_receivable: '總應收延滯息',
                    adjust_different: '調整後左右差額',
                }
            }
            else {
                return {
                    left: {
                        bank_balance: 'Bank Balance',
                        virtual_account_balance: 'Virtual Account Balance',
                        principal_balance: 'Principal Balance',
                        delay_principal_balance: 'Delay Principal Balance',
                        transfer_in_loss: 'Transfer In: Loss',
                        transfer_out_loss: 'Transfer Out: Loss',
                        platform_fee: 'Platform Fee',
                        transfer_out_fee: 'Transfer Out: Fee',
                        donation_expense: 'Donation Expense',
                        bank_verify_fee: 'Bank Verify Fee',
                        legal_collection_platform_fee: 'Legal Collection Platform Fee',
                        total: 'Total',
                        other: 'Other lost',
                    },
                    right: {
                        sales_tax: 'Sales Tax',
                        interest_income: 'Interest Income',
                        transfer_in_gain: 'Transfer In: Gain',
                        prepayment_compensation: 'Prepayment Compensation',
                        delay_interest_income: 'Delay Interest Income',
                        transfer_out_gain: 'Transfer Out: Gain',
                        total: 'Total',
                        other: 'Other income',
                    },
                    balance: 'Balance',
                    all_principal_repaid: 'All Principal Repaid',
                    all_loan_amount: 'All Loan Amount',
                    all_interest_receivable: 'All Interest Receivable',
                    all_delay_interest_receivable: 'All Delay Interest Receivable',
                    adjust_different: 'Left and right different',
                }
            }
        }
    },
    mounted() {
        var self = this;
        $('#date1').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.end_date = this.value });

        $('#date2').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.start_date = this.value });

        $('#date3').datepicker({
            'format': 'yyyy-mm-dd',
        }).on('change', function () { self.searchform.end_date = this.value });
    },
    methods: {
        doDictSearch() {
            const { end_date, user_id_int } = this.searchform
            const string = Object.entries({ end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get replayment_schedule
            this.is_waiting_response = true
            axios.get('/admin/erp/get_balance_sheet_dict?' + string).then(({ data }) => {
                this.dictData = data
            }).catch((error) => {
                alert('子系統錯誤或無回應: ' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        doDiffSearch() {
            const { start_date: a_end_date, end_date: b_end_date, user_id_int } = this.searchform
            const string = Object.entries({ a_end_date, b_end_date, user_id_int })
                .filter(([key, value]) => value !== '')
                .map(([key, value]) => `${key}=${value}`)
                .join('&')
            // axios get replayment_schedule
            this.is_waiting_response = true
            axios.get('/admin/erp/get_balance_sheet_diff?' + string).then(({ data }) => {
                this.diffData = data
            }).catch((error) => {
                alert('子系統錯誤或無回應: ' + error)
            }).finally(() => {
                this.is_waiting_response = false
            })
        },
        format(value) {
            return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')
        },
        textClass(value) {
            if (value > 0) {
                return 'text-success'
            }
            else if (value < 0) {
                return 'text-danger'
            }
            else {
                return ''
            }
        },
    }
})
