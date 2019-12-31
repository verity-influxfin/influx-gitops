class User
{
	constructor(user) {
		this.id = user.id;
		this.name = user.name;
		this.profileImage = user.profile_image;
		this.gender = user.sex;
		this.birthday = user.birthday;
		this.registeredAt = user.created_at;
		this.judicial_id = user.judicial_id;
		this.setIdCard(user)
		this.setSchool(user)
		this.setContact(user)
		this.setInstagram(user)
		this.setFacebook(user)
		this.setMarriage(user)
	}

	setIdCard(user) {
		if (!user.id_card) return;
		this.idCard = {}
		this.idCard.id = user.id_card.id;
		this.idCard.issuedAt = user.id_card.issued_at;
	}

	setSchool(user) {
		if (!user.school) return;
		this.school = {}
		this.school.name = user.school.name;
		this.school.system = user.school.system;
		this.school.department = user.school.department;
		if (user.school.graduate_at) this.school.graduateAt = user.school.graduate_at;
	}

	setContact(user) {
		if (!user) return;
		this.contact = {}
		this.contact.phone = user.phone;
		this.contact.email = user.email;
		this.contact.address = user.address
	}

	setInstagram(user) {
		if (!user.instagram) return;
		this.instagram = {}
		this.instagram.id = user.instagram.id;
		this.instagram.username = user.instagram.username;
		this.instagram.profileImage = user.instagram.profile_image;
	}

	setFacebook(user) {
		if (!user.facebook) return;
		this.facebook = {}
		this.facebook.id = user.facebook.id;
		this.facebook.username = user.facebook.username;
	}

	setMarriage(user) {
		if (!user.marriage) return;
		this.marriage = {};
		this.marriage.name = user.marriage.name;
		this.marriage.phone = user.marriage.phone;
	}

	getFbProfilePicture() {
		if (!this.facebook) return;
		return 'https://graph.facebook.com/' + this.facebook.id + '/picture?type=large';
	}

	getRegisteredAtAsDate() {
		return new DateTime(this.registeredAt).values();
	}

	isMarried() {
		return this.marriage && this.marriage.name;
	}
}
