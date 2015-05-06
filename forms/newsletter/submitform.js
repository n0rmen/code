
function submitForm(e, callback){
	e.preventDefault();
	var form = $(e.target),
		formElements = form.find('input,select,textarea'),
		formValid = true;
	
	/* Reinit */
	form.find('.invalid').removeClass('invalid');
	form.find('.message,.result').remove();
	
	var Modernizr = Modernizr || false;
	
	/* Fallback for required input */
	if(Modernizr && !Modernizr.input.required) formElements.each(function(){
			if(this.getAttribute('required') != null && this.value.length == 0){
				$(this).addClass('invalid');
				formValid = false;
			}
		});
	
	/* Fallback for email inputtype */
	if(Modernizr && !Modernizr.inputtypes.email) formElements.each(function(){
			var regexp = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(this.getAttribute('type') == "email" && !regexp.test(this.value)){
				$(this).addClass('invalid');
				formValid = false;
			}
		});
	
	/* Fallback for url inputtype */
	if(Modernizr && !Modernizr.inputtypes.url) formElements.each(function(){
			var regexp = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
			if(this.getAttribute('type') == "url" && !regexp.test(this.value)){
				$(this).addClass('invalid');
				formValid = false;
			}
		});
	
	/* Fallback for date inputtype */
	if(Modernizr && !Modernizr.inputtypes.date) formElements.each(function(){
			var regexp = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/,
				regexp2 = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
			if(this.getAttribute('type') == "date" && this.value.length > 0 && regexp.test(this.value)){
				this.value = this.value.substr(6,4) + "-" + this.value.substr(3,2) + "-" + this.value.substr(0,2);
			}
			else if(this.getAttribute('type') == "date" && this.value.length > 0 && !regexp2.test(this.value)){
				$(this).addClass('invalid');
				formValid = false;
			}
		});
	
	/* Submit */
	if(formValid){
		var formMethod = form.attr('method'),
			formAction = form.attr('action');
		
		if(window.FormData){
			var data = new FormData(e.target),
				processData = false,
				contentType = false;
		}
		else {
			var data = {},
				processData = true,
				contentType = "application/x-www-form-urlencoded; charset=UTF-8";
			formElements.each(function(){
				if(this.type == "radio" && this.checked) data[this.name] = this.value;
				else if(this.type == "checkbox") data[this.name] = this.checked;
				else if(this.type == "file") console.error("FILE UPLOAD DISABLED");
				else data[this.name] = this.value;
			});
		}
		
		$.ajax({
			type: formMethod,
			url: formAction,
			dataType: "json",
			processData: processData,
			contentType: contentType,
			data: data
		})
		.done(callback)
		.fail(function(){ console.error("FORM VALIDATION ERROR") });
	}
	
	return false;
}