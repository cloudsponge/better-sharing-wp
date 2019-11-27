export default class AutomateWoo {

	init($) {
		cloudsponge.init({
			afterInit: this.afterInit
		});

		cloudsponge.launch('gmail');
	}

	afterInit() {
		console.log('afterInit');
	}

}

(function($){

	$(document).ready(function(){
		var autoWoo = new AutomateWoo();
		autoWoo.init($);
	});

}(jQuery));