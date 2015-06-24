/**
 * @file utils.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2014 Cyril Vermande
 *
 * Utils
*/

/**
 * Checks if var is null
 * @param arg (mixed) Var to check
 * @return (boolean)
 */
function isNull(arg){
	return arg === null;
}

/**
 * Checks if var is empty or null or false
 * @param arg (mixed) Var to check
 * @return (boolean)
 */
function isEmpty(arg){
	return arg === null || arg === false || arg === 0 || arg === '' || arg.length === 0;
}

/**
 * Checks if var is an array
 * @param arg (mixed) Var to check
 * @return (boolean)
 */
function isArray(arg){
	return arg instanceof Array;
}

/**
 * Checks if var is a function
 * @param arg (mixed) Var to check
 * @return (boolean)
 */
function isFunction(arg){
	return arg instanceof Function;
}

/**
 * Checks if var is an object (and not an array)
 * @param arg (mixed) Var to check
 * @return (boolean)
 */
function isObject(arg){
	return arg instanceof Object && !(arg instanceof Array) && !(arg instanceof Function);
}

/**
 * Checks if a value exists in an array
 * @param value (mixed) The searched value
 * @param array The array
 * @return (boolean)
 */
function inArray(value, array){
	for(var i in array){
		if(array[i] === value) return true;
	}
	return false;
}

/**
 * Duplicate a JS object
 * @param (object) obj
 * @return (object)
 */
function clone(obj){
	if(typeof obj !== "object" || obj === null) return obj;
	var newObj = obj.constructor();
	for(var prop in obj){
		if(obj.hasOwnProperty(prop)) newObj[prop] = clone(obj[prop]);
	}
	return newObj;
}

/**
 * Compare 2 object
 * @param (object) obj1
 * @param (object) obj2
 * @return (boolean) True if obj1 is the same that obj2 (but not specialy the same instance)
 */
function compare(obj1, obj2){
	for(var prop in obj1){
		if(obj1.hasOwnProperty(prop)){
			if(typeof obj1[prop] !== typeof obj2[prop]) return false;
			else if(typeof obj1[prop] === "object"){
				if(!compare(obj1[prop], obj2[prop])) return false;
			}
			else if(obj1[prop] !== obj2[prop]) return false;
		}
	}
	
	for(var prop in obj2){
		if(obj2.hasOwnProperty(prop)){
			if(typeof obj1[prop] !== typeof obj2[prop]) return false;
			else if(typeof obj2[prop] === "object"){
				if(!compare(obj1[prop], obj2[prop])) return false;
			}
			else if(obj1[prop] !== obj2[prop]) return false;
		}
	}
	
	return true;
}

/**
 * Format Date object
 * @param (string) Format
 * @return (string) Formatted date
 * See https://github.com/jacwright/date.format for original source code
 */
Date.prototype.utc = false;
Date.prototype.dayNames = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
Date.prototype.dayShortNames = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"];
Date.prototype.monthNames = ["Janvier", "FÃ©vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "AoÃ»t", "Septembre", "Octobre", "Novembre", "DÃ©cembre"];
Date.prototype.monthShortNames = ["Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec"];
Date.prototype.format = function format(format){
	var _date = this;
	var replacements = {
		l: function(){ return this.dayNames[this.getDay()]; },
		D: function(){ return this.dayShortNames[this.getDay()]; },
		F: function(){ return this.monthNames[this.getMonth()]; },
		M: function(){ return this.monthShortNames[this.getMonth()]; },
		Y: function(){ return this.utc ? this.getUTCFullYear() : this.getFullYear(); },
		y: function(){ return this.utc ? this.getUTCYear() : this.getYear(); },
		m: function(){ var month = this.utc ? this.getUTCMonth() : this.getMonth(); return (month < 9 ? '0' : '') + (month+1); },
		d: function(){ var date = this.utc ? this.getUTCDate() : this.getDate(); return (date < 10 ? '0' : '') + date; },
		H: function(){ var hours = this.utc ? this.getUTCHours() : this.getHours(); return (hours < 10 ? '0' : '') + hours; },
		G: function(){ return this.utc ? this.getUTCHours() : this.getHours(); },
		i: function(){ var minutes = this.getMinutes(); return (minutes < 10 ? '0' : '') + minutes; },
		s: function(){ var seconds = this.getSeconds(); return (seconds < 10 ? '0' : '') + seconds; }
	};
	return format.replace(/(\\?)(.)/g, function(match, esc, chr){
		return (esc === '' && replacements[chr]) ? replacements[chr].call(_date) : chr;
	});
}

/**
 * Generate random integer between 2 numbers
 * @param (int) a Min
 * @param (int) b Max
 * @return (int) Random number
 */
function rand(a,b) {
	return Math.floor(Math.random() * (b - a + 1) + a);
}

/**
 * Escape HTML special characters (< > " ' &)
 * @param (string) value The string to escape
 * @return (string) The escaped string
 */
function htmlspecialchars(value) {
	if(typeof(value) === "string") return value.replace(/&/g, "&amp;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;");
	return "";
}

/**
 * Convert an object in instance of the specified class
 * @param (string) constructor Class
 * @param (object) object Data
 * @return (object) instance
 * Usage: var foo = createInstanceOf(Foo, {id: 1, name: "bar"});
 */
function createInstanceOf(constructor, object){
	var instance = new constructor();
	for(var prop in object){
		if(object.hasOwnProperty(prop)) instance[prop] = object[prop];
	}
	return instance;
}
