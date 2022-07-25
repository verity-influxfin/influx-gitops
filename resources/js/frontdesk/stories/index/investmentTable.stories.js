import investmentTable from '@/component/investmentTable.vue';

export default {
    title: 'Components/investmentTable',
    component: investmentTable,
};

const Template = (args, { argTypes }) => ({
    components: { investmentTable },
    template: '<investmentTable />',
});

export const Primary = Template.bind({});
Primary.parameters = {
    design: {
        type: 'figma',
        url: 'https://www.figma.com/file/UtVF1YQzvyirmc59r1e6fa/%E9%A6%96%E9%A0%81-guideline?node-id=2732%3A2793',
    },
}

