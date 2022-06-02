import pureProgressOverview from '@/component/enterpriseUpload/pureProgressOverview';
export default {
    title: '企金/進度條 (progressOverview)',
    component: pureProgressOverview,
};
const Template = (args, { argTypes }) => ({
    components: { pureProgressOverview },
    props: Object.keys(argTypes),
    template: `<pure-progress-overview v-bind="$props"/>`,
});

export const 預設 = Template.bind({})
預設.args = {
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
    }
}
export const 無進度 = Template.bind({})
無進度.args = {
    progressOverview: {
        title: '資料提供進度',
        principal: {
            title: '負責人資料更新',
            process: {
                now: 0,
                all: 6,
                percentage: '0%'
            }
        },
        company: {
            title: '公司資料更新',
            process: {
                now: 0,
                all: 8,
                percentage: '0%'
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
}
export const 完成 = Template.bind({})
完成.args = {
    progressOverview: {
        title: '資料提供進度',
        principal: {
            title: '負責人資料更新',
            process: {
                now: 6,
                all: 6,
                percentage: '100%'
            }
        },
        company: {
            title: '公司資料更新',
            process: {
                now: 8,
                all: 8,
                percentage: '100%'
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
}
