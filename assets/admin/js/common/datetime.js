class DateTime
{
	// GMT timestamp
	constructor(timestamp) {
		this.date = new Date(timestamp * 1000);
		this.years = this.date.getFullYear();
		this.months = this.date.getMonth();
		this.days = this.date.getDate();
		this.hours = this.date.getHours();
		this.minutes = this.date.getMinutes();
		this.seconds = this.date.getSeconds();
	}

	values() {
		return this.years + "-" + this.months + "-" + this.days + " " + this.hours + ":" + this.minutes + ":" + this.seconds;
	}
}
