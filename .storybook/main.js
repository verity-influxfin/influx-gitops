const path = require('path')

module.exports = {
    "stories": [
        "../resources/js/frontdesk/stories/**/*.stories.mdx",
        "../resources/js/frontdesk/stories/**/*.stories.@(js|jsx|ts|tsx)"
    ],
    "addons": [
        "@storybook/addon-links",
        "@storybook/addon-essentials",
        "@storybook/addon-interactions",
        "@storybook/addon-viewport",
        "storybook-addon-designs"
    ],
    "framework": "@storybook/vue",
    webpackFinal: async (config, { configType }) => {
        // `configType` has a value of 'DEVELOPMENT' or 'PRODUCTION'
        // You can change the configuration based on that.
        // 'PRODUCTION' is used when building the static version of storybook.

        // Make whatever fine-grained changes you need
        config.module.rules.push({
            test: /\.scss$/,
            use: ['style-loader', 'css-loader', 'sass-loader'],
            include: path.resolve(__dirname, '../resources/js/frontdesk'),
        });
        config.module.rules.push({
            test: /\.(css|scss)$/,
            use: ['style-loader', 'css-loader', 'sass-loader'],
        });
        config.resolve.alias = {
            ...config.resolve.alias,
            '@': path.resolve(__dirname, '../resources/js/frontdesk'),
            'images': path.join(__dirname, '../resources/js/frontdesk/asset/images'),
        };
        // Return the altered config
        return config;
    },
}
