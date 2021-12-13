Vue.component('v-page', {
  props: [ 'data','maxLength' ],
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
		const length = last_page
		const maxLength = this.maxLength ?? 7
		if (length < maxLength) {
			return this.range(1,this.length)
		}
		const even = maxLength % 2 === 0 ? 1 : 0
		const left = Math.floor(maxLength / 2)
		const right = length - left + 1 + even
		if (current_page > left && current_page < right) {
			const firstItem = 1
			const lastItem = length
			const start = current_page - left + 2
			const end = current_page + left - 2 - even
			const secondItem = start - 1 === firstItem + 1 ? 2 : '...'
			const beforeLastItem = end + 1 === lastItem - 1 ? end + 1 : '...'

			return [1, secondItem, ...this.range(start, end), beforeLastItem, length]
		} else if (current_page === left) {
			const end = current_page + left - 1 - even
			return [...this.range(1, end), '...', length]
		} else if (current_page === right) {
			const start = current_page - left + 1
			return [1, '...', ...this.range(start, length)]
		} else {
			return [
				...this.range(1, left),
				'...',
				...this.range(right, length),
			]
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
            <a href="#" v-if="isNumber(page)" @click.prevent="change_page(page)">{{page}}</a>
            <a v-else>{{page}}</a>
			
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
	},
	range(from, to) {
		  const range = []
		  from = from > 0 ? from : 1
		  for (let i = from; i <= to; i++) {
			  range.push(i)
		  }
		  return range
	},
	isNumber(n) {
		return !isNaN(Number(n))
	}
  }
});

Vue.component('blog-post', {
  props: ['title'],
  template: '<h3>{{ title }}</h3>'
});
