$(function() {
	$('#replay_restaurant').on('click', function() {
		$('#results').removeClass();
		$('#results').hide(1);	
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(getRandomRestaurant);
	    } else { 
	        x.innerHTML = "Geolocation is not supported by this browser.";
	    }
	});
});

