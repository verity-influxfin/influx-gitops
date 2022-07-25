import investmentTable  from '@/component/investmentTable.vue';

export default {
    title: 'Components/investmentTable',
    component: investmentTable,
};

const Template = (args, { argTypes }) => ({
    components: { investmentTable },
    template: '<investmentTable />',
});

export const Primary = Template.bind({});

