// Addons
import AutomateWoo from "./AutomateWoo/assets";
import CouponReferral from "./CouponReferralProgram/assets";


(function($){

	$(document).ready(function(){
		var autoWoo = new AutomateWoo();
		autoWoo.init($);

		var couponRef = new CouponReferral();
		couponRef.init($);

	});

}(jQuery));