import stepGroup  from '@/component/index/stepGroup.vue';

export default {
    title: 'Components/stepGroup',
    component: stepGroup,
};

const Template = (args, { argTypes }) => ({
    components: { stepGroup },
    props: Object.keys(argTypes),
    template: '<stepGroup v-bind="$props"/>',
});

export const Default = Template.bind({});
Default.args = {  };
