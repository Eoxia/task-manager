var expect = require('chai').expect;
var Nightmare = require('nightmare');

module.exports.goToLogin = function(nightmare, cb) {
	describe('Login', function() {
		it('It shoud login', function(done) {
			nightmare
				.goto('http://127.0.0.1/wordpress/wp-admin')
				.wait('#login')
				.type('input[name="log"]', 'a')
				.type('input[name="pwd"]', 'a')
				.click('input[type="submit"]')
				.wait('#adminmenu')
				.evaluate(function() {
					return;
				})
				.then(function() {
					done();
					cb();
				})
				.catch(function(error) {
					console.error('Search failed:', error);
					done(error);
					cb();
				})
		});
	});
};

module.exports.goToApp = function(nightmare, cb) {
	describe('App', function() {
		it('It should go to TheEPI main page', function( done ) {
			nightmare
				.goto('http://127.0.0.1/wordpress/wp-admin/admin.php?page=wpeomtm-dashboard')
				.wait('.wpeo-project-wrap')
				.evaluate(function() {
					return;
				})
				.then(function() {
					done();
					cb();
				})
				.catch(function(error) {
					done(error);
					cb();
				})
		});
	});
}
