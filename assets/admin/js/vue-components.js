Vue.component('v-page', {
  props: [ 'data' ],
  computed: {
    has_prev: function () {
        return this.$props['data']['current_page'] > 1;
    },
    has_next: function () {
        return this.$props['data']['current_page'] < this.$props['data']['last_page'];
    },
    page_range: function () {
        let current_page = this.$props['data']['current_page'];
        let last_page = this.$props['data']['last_page'];

        if (current_page == 1) {
            switch (last_page) {
                case 1:
                    return [1];
                case 2:
                    return [1, 2];
                default:
                    return [1, 2, 3];
            }
        } else {
            let start = last_page - 2;
            start = start < 1 ? 1 : start;

            let retval = [];
            for (let i = start;i <= last_page;i++) {
                retval.push(i);
            }
            return retval;
        }
    }
  },
  template: `
<nav>
    <ul class="pagination">
        <li :class="{disabled: !has_prev}">
            <a href="#" @click.prevent="prev_page()">
                <span>&laquo;</span>
            </a>
        </li>
        <li v-for="page in page_range" :class="{active: page==data.current_page}">
            <a href="#" @click.prevent="change_page(page)">{{page}}</a>
        </li>
        <li :class="{disabled: !has_next}">
            <a href="#" @click.prevent="next_page()">
                <span>&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
`,
  methods: {
    change_page: function (page) {
        if (this.$props['data']['current_page'] != page) {
            this.$emit('change_page', page);
        }
    },
    next_page: function () {
        if (this.has_next) {
            this.change_page(this.$props['data']['current_page'] + 1);
        }
    },
    prev_page: function () {
        if (this.has_prev) {
            this.change_page(this.$props['data']['current_page'] - 1);
        }
    }
  }
});

Vue.component('blog-post', {
  props: ['title'],
  template: '<h3>{{ title }}</h3>'
});