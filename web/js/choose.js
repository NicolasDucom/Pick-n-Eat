var numberOfRestaurants = 3;
var restaurants = false;
var choosenRestaurant = false;

$(function() {
	$( document ).ready(function() {
		$(".dropdown-button").dropdown();
	});
	
	$('#add_restaurant').on('click', function() {
		$('#restaurants_list').append('<li class="collection-item"><div><input id="restaurants['+(++numberOfRestaurants)+']" class="restaurant" type="text" required=""><label for="restaurants['+numberOfRestaurants+']">Restaurant</label></div></li>');
	});

	$('#go').on('click', function() {
		restaurants = $('input.restaurant');
		
		if (restaurants.length > 0) {
			choosenRestaurant = restaurants[randomIntFromInterval(0, restaurants.length-1)].value;

			$('#restaurant_title').text('Restaurant '+ choosenRestaurant);
			$('#choices').addClass('animated zoomOutDown');
		}
	});

	$('#choices').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
		$('#choices').hide(1, function() {
			$('#loading').show(1, function() {
				$('#loading').addClass('animated bounceIn');
			});
		});
	});

	$('#loading').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
		setTimeout(function(){
			$('#loading').fadeOut(420, function() {
				$('#results').show(1, function() {
					$('#results').addClass('animated bounceIn');
				});
			});
		}, 1050);
	});

	$('#retry_button').on('click', function() {
		var previousRestaurant = choosenRestaurant;

		while (previousRestaurant == choosenRestaurant) {
			choosenRestaurant = restaurants[randomIntFromInterval(0, restaurants.length-1)].value;
		}
		
		$('#results').removeClass();
		//$("#restaurant_image").attr("src", "{{ app.request.basepath }}/img/choose3.jpg");
		$('#restaurant_title').text('Restaurant '+ choosenRestaurant);
		$('#results').addClass('animated shake');

		$('#retry_button').fadeOut(200);
	});
});

function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}