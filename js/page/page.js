/**
 * @file page.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2015 Cyril Vermande
 *
 * Page micro framework
*/
var Page = {
	pageTitle: "Page.js",
	setTitle: function(title){
		if(title) document.title = title + " | " + this.pageTitle;
		else document.title = this.pageTitle;
		return this;
	},
	setUrl: function(url, title){
		url = url || "";
		title = title || Page.pageTitle;
		if(window.history.pushState) window.history.pushState(null, title , url);
		else window.location.hash = "#" + url;
		return this;
	},
	setContent: function(html){
		if(html) document.getElementById('page-content').innerHTML = html;
		return this;
	},
	sendNotification: function(nofitication){
		return this;
	},
	showLoading: function(){
		document.getElementById('loading').style.display = "block";
		return this;
	},
	hideLoading: function(){
		document.getElementById('loading').style.display = "none";
		return this;
	},
	scrollTo: function(pos){
		window.scrollTo(0, pos);
		return this;
	}
}