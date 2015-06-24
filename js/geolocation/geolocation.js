/**
 * @file geolocation.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2014 Cyril Vermande
 *
 * Geolocation
*/
var Geolocation = {
	currentPosition: false,
	currentLocale: false,
	settings:{
		localeListurl: "getLocale.php"
	},
	
	/**
	 * Initialize Geolocation
	 * @param options (object)
	 */
	init: function(options){
		if(options.localeListurl) this.settings.localeListurl = options.localeListurl;
	},
	
	/**
	 * Get current position
	 * @param callback (function) Call with object as argument
	 */
	getCurrentPosition: function(callback){
		var _this = this;
		if(this.currentPosition){
			if(callback) callback(this.currentPosition);
		}
		else if(navigator.geolocation){
			try{
				navigator.geolocation.getCurrentPosition(function(position){
					_this.currentPosition = position;
					if(callback) callback(_this.currentPosition);
				});
			}
			catch(e){
				console.error("Error: geolocation not supported");
				if(callback) callback({ error: { message: "Error: geolocation is not supported" } });
			}
		}
		else{
			console.error("Error: geolocation not supported");
			if(callback) callback({ error: { message: "Error: geolocation is not supported" } });
		}
	},
	
	/**
	 * Get current locale
	 * @param callback (function) Call with object as argument
	 */
	getCurrentLocale: function(callback){
		var _this = this;
		if(this.currentLocale){
			if(callback) callback(this.currentLocale);
		}
		else{
			this.getCurrentPosition(function(position){
				try{
					if(!position || !position.coords) throw "Current position is not defined";
					if(!position.coords.latitude) throw "Latitude is not defined";
					if(!position.coords.longitude) throw "Longitude is not defined";
					
					if (window.XMLHttpRequest) var xhr = new XMLHttpRequest();
					else if (window.ActiveXObject) var xhr = new ActiveXObject("Microsoft.XMLHTTP");
					else throw "AJAX is not supported";
					
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4){
							if(xhr.status == 200){
								var response = JSON.parse(xhr.responseText);
								_this.currentLocale = response;
								if(callback) callback(response);
							}
							else if(xhr.status >= 400){
								throw xhr.status + " " + xhr.statusText;
							}
						}
					}
					xhr.open("get", _this.settings.localeListurl + "?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude, true);
					xhr.send(null);
				}
				catch(e){
					console.error("Error: " + e);
					if(callback) callback({ error: { message: "Error: " + e } });
				}
			});
		}
	}
};
