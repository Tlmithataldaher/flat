<!DOCTYPE html>
<html >
	<head>
		<meta charset="utf-8">
		<title>RegistrationForm_v1 by Colorlib</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

		<!-- STYLE CSS -->
		<link rel="stylesheet" href="css/style.css">
	</head>

	<body>

		<div class="wrapper" style="background-image: url('1.jpg');">
			<div class="inner">
				<div class="image-holder">
						<div style="display: none">
								<input id="origin-input" class="controls" type="text"
									placeholder="Enter an origin location">
						
								<input id="destination-input" class="controls" type="text"
									placeholder="Enter a destination location">
						
								<div id="mode-selector" class="controls">
								  <input type="radio" name="type" id="changemode-walking" checked="checked">
								  <label for="changemode-walking">Walking</label>
						
								  <input type="radio" name="type" id="changemode-driving">
								  <label for="changemode-driving">Driving</label>
								</div>
							</div>
					<div id="map"></div>
					
				</div>
				<form action="" dir="rtl">
					<h3>الطلب</h3>
					<div class="form-wrapper">
						<input type="text" placeholder="اسم المستلم" class="form-control">
						<input type="text" placeholder="رقم المستلم " class="form-control">
					</div>
					
					<div class="form-wrapper">
						<input type="text" placeholder="وصف الطلب" class="form-control">
					</div>
					
					<div class="form-wrapper">
						<select name="" id="" class="form-control">
							<option value="" disabled selected> نوع الطلب</option>
							<option value="أثاث">أثاث</option>
							<option value="مأكولات">مأكولات</option>
							<option value="أخرى">أخرى</option>
						</select>
					</div>
					<div class="form-wrapper">
						<select name="" id="" class="form-control">
							<option value="" disabled selected>وسيلة النقل</option>
							<option value="سيارة">سيارة</option>
							<option value="شاحنة">شاحنة</option>
							<option value="دراجة">دراجة</option>
						</select>
					</div><div class="form-wrapper">
						<select name="" id="" class="form-control">
							<option value="" disabled selected>الموصل </option>
							<option value="أحمد">أحمد</option>
							<option value="محمد">محمد</option>
							<option value="علي">علي</option>
						</select>
					</div>
					<div id="output" class="result-table"></div>
					
					<button>Register
					</button>

					
				</form>
			</div>
		</div>
		<script>
			document.getElementById("output").style.display = "none";
			function initMap() {
			  var map = new google.maps.Map(document.getElementById('map'), {
				mapTypeControl: false,
				center: {lat: 15.298819, lng: 44.181877},
				zoom: 16,
				// mapTypeId: google.maps.MapTypeId.ROADMAP
			  });
			
			  new AutocompleteDirectionsHandler(map);
			}
			
			/**
			 * @constructor
			 */
			function AutocompleteDirectionsHandler(map) {
			  this.map = map;
			  this.originPlaceId = null;
			  this.destinationPlaceId = null;
			  this.travelMode = 'WALKING';
			  this.directionsService = new google.maps.DirectionsService;
			  this.directionsRenderer = new google.maps.DirectionsRenderer;
			  this.directionsRenderer.setMap(map);
			  
			  var originInput = document.getElementById('origin-input');
			  var destinationInput = document.getElementById('destination-input');
			  var modeSelector = document.getElementById('mode-selector');
			
			  var originAutocomplete = new google.maps.places.Autocomplete(originInput);
			  // Specify just the place data fields that you need.
			  originAutocomplete.setFields(['place_id']);
			
			  var destinationAutocomplete =
				  new google.maps.places.Autocomplete(destinationInput);
			  // Specify just the place data fields that you need.
			  destinationAutocomplete.setFields(['place_id']);
			
			  this.setupClickListener('changemode-walking', 'WALKING');
			  this.setupClickListener('changemode-driving', 'DRIVING');
			
			  this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
			  this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
			
			  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
			  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
				  destinationInput);
			  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
			}
			
			// Sets a listener on a radio button to change the filter type on Places
			// Autocomplete.
			AutocompleteDirectionsHandler.prototype.setupClickListener = function(
				id, mode) {
			  var radioButton = document.getElementById(id);
			  var me = this;
			
			  radioButton.addEventListener('click', function() {
				me.travelMode = mode;
				me.route();
			  });
			};
			
			AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(
				autocomplete, mode) {
			  var me = this;
			  autocomplete.bindTo('bounds', this.map);
			
			  autocomplete.addListener('place_changed', function() {
				var place = autocomplete.getPlace();
			
				if (!place.place_id) {
				  window.alert('Please select an option from the dropdown list.');
				  return;
				}
				if (mode === 'ORIG') {
				  me.originPlaceId = place.place_id;
				} else {
				  me.destinationPlaceId = place.place_id;
				}
				me.route();
			  });
			};
			
			AutocompleteDirectionsHandler.prototype.route = function() {
			  if (!this.originPlaceId || !this.destinationPlaceId) {
				return;
			  }
			  var me = this;
			 
			  this.directionsService.route(
				  {
					origin: {'placeId': this.originPlaceId},
					destination: {'placeId': this.destinationPlaceId},
					travelMode: this.travelMode,
					unitSystem: google.maps.UnitSystem.METRIC
				  },
				  function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {

						//display route
						me.directionsRenderer.setDirections(response);

					 //Get distance and time          
					  document.getElementById("output").innerHTML="<div class='result-table'> المسافة : " + response.routes[0].legs[0].distance.text + ". الزمن :" + response.routes[0].legs[0].duration.text + ".</div>";
					  document.getElementById("output").style.display = "block";
                    
                    
					} else {
					  window.alert('Directions request failed due to ' + status);
					}
					window.location.href="order.php?uid=1&orginalId=me.originPlaceId&destinationId=me.destinationPlaceId";
					alert( me.originPlaceId + "    " + me.destinationPlaceId);
				  });
			};
			
				</script>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJuIBH_cjgQuJE7HUUE1EA0jbF176yZXA&libraries=places&callback=initMap"
					async defer></script>
				
	</body>
</html>