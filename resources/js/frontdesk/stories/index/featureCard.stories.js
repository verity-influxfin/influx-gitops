import featureCard from '@/component/index/featureCard';
import Icon from '@/asset/images/index/link-1.png'
export default {
    title: '首頁/特色介紹卡片 (featureCard)',
    component: featureCard,
    argTypes: {
        icon: {
            control: {
                type: 'file'
            }
        }
    }
};
const Template = (args, { argTypes }) => ({
    components: { featureCard },
    props: Object.keys(argTypes),
    template: `<feature-card v-bind="$props"/>`,
});

export const 預設 = Template.bind({})
預設.args = {
    icon: Icon,
    title: '獲得多家銀行搶先合作',
    info: '我們提供使用者最簡單便利安全的金融科技體驗......'
}
