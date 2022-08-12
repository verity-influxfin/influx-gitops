import { INITIAL_VIEWPORTS } from '@storybook/addon-viewport';
import '!style-loader!css-loader!sass-loader!../resources/scss/frontdesk/layout.scss';

export const parameters = {
  actions: { argTypesRegex: "^on[A-Z].*" },
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/,
    },
  },
  viewport: {
    viewports: INITIAL_VIEWPORTS,
  },
  backgrounds: {
    default:'gray',
    values: [
      { name: 'light', value: '#ffffff' },
      { name: 'blue', value: '#f3f9fc' },
      { name: 'gray', value: '#707070' },
    ],
  }
}
