/**
 * Usage : ajax(url [,success [, error]]) or ajax(settings)
 */

function ajax(){
	if(typeof arguments[0] == "string"){
		var params = {
			method: "get",
			contentType: "text",
			url: arguments[0],
			data: null,
			success: arguments[1] || null,
			error: arguments[2] || null
		};
	}
	else if(typeof arguments[0] == "object"){
		var params = {
			method: arguments[0].method || "get",
			contentType: arguments[0].contentType || "text",
			url: arguments[0].url || null,
			data: arguments[0].data || [],
			success: arguments[0].success || null,
			error: arguments[0].error || null
		};
		
		if(!params.method.match(/^(get|post|put|patch|delete)$/i)) throw "Method \"" + params.method + "\" undefined";
		if(typeof params.url != "string" || params.url.length == 0) throw "URL \"" + params.url + "\" undefined";
	}
	else throw "Argument undefined";
	
	if (window.XMLHttpRequest) var xhr = new XMLHttpRequest();
	else if (window.ActiveXObject) var xhr = new ActiveXObject("Microsoft.XMLHTTP");
	else throw "AJAX disabled";
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			if(xhr.status == 200){
				switch(params.contentType){
					case "json" : var response = JSON.parse(xhr.responseText); break;
					case "xml" : var response = xhr.responseXML; break;
					default : var response = xhr.responseText;
				}
				if(params.success) params.success(response);
			}
			else if(xhr.status >= 400){
				if(params.error) params.error(xhr.status);
				else throw "Error " + xhr.status;
			}
		}
	}
	xhr.open(params.method, params.url, true);
	if(params.method.match(/^(post|put|patch)$/i)){
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		var tmp = [];
		for(var prop in params.data) tmp.push(encodeURIComponent(prop) + "=" + encodeURIComponent(params.data[prop]));
		params.data = tmp.join("&");
	}
	xhr.send(params.data);
}
