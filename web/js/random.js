$(function() {
	$('#random_restaurant').on('click', function() {
		$('#random').fadeOut(200);
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(getRandomRestaurant);
	    } else { 
	        x.innerHTML = "Geolocation is not supported by this browser.";
	    }
	});

	$('#replay_restaurant').on('click', function() {
		$('#results').fadeOut(200);
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(getRandomRestaurant);
	    } else { 
	        x.innerHTML = "Geolocation is not supported by this browser.";
	    }
	});
});

