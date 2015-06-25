/**
 * @file sdc.api.js
 * @author Cyril Vermande (madislak [AT] yahoo.fr)
 * @copyright All rights reserved 2014 Cyril Vermande
 * 
 * Class Sdc
 * Usage : var sdc = new Sdc({ apikey:"52d738c04107341b4bb345dcfa52ec3f5" });
 */
function Sdc(options){
	if(typeof options === "undefined" || options.apikey === undefined || options.apikey.length === 0) throw new Error("API key is missing");
	
	this.url = options.url || "api/1.2/";
	this.dataType = "json"; // json or jsonp
	this.authUsername = options.username || null;
	this.authPassword = options.password || null;
	this.apikey = options.apikey;
	this.log = options.log;

	this.sanitizeUrl = function(url){
		return url;
	}
}

/**
 * Get data
 * @param url (string) URL of the resource to get
 * @param data (object) Data to add to the query
 * @param callback (function) Callback function
 */
Sdc.prototype.get = function(url, data, callback){
	if(typeof data === "function" && !callback) callback = data;
	var url = this.url + this.sanitizeUrl(url);
	data.apikey = this.apikey;
	$.ajax({
		type: "get",
		url: url,
		username: this.authUsername,
		password: this.authPassword,
		dataType: this.dataType,
		data: data
	}).done(callback);
	if(this.log) console.log("API: get " + url);
}

/**
 * Create/update an item
 * @param url (string) URL of the resource to create/update
 * @param data (object) Data to post
 * @param callback (function) Callback function
 */
Sdc.prototype.post = function(url, data, callback){
	var url = this.url + this.sanitizeUrl(url);
	data.apikey = this.apikey;
	$.ajax({
		type: "post",
		url: url,
		username: this.authUsername,
		password: this.authPassword,
		dataType: this.dataType,
		data: data
	}).done(callback);
	if(this.log) console.log("API: POST " + url);
}

/**
 * Delete an item
 * @param url (string) URL of the resource to delete
 * @param callback (function) Callback function
 */
Sdc.prototype.del = function(url, callback){
	var url = this.url + this.sanitizeUrl(url);
		url += "?apikey=" + this.apikey;
	$.ajax({
		type: "delete",
		url: url,
		username: this.authUsername,
		password: this.authPassword,
		dataType: this.dataType,
		data: {}
	}).done(callback);
	if(this.log) console.log("API: delete " + url);
}