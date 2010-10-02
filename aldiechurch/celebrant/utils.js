	function get_response_from(url, function_name, args) {
		/*  Given:
				url				- the location of a php library that has been 'ajaxified' using 'Ajaxify.php'						
				function_name 	- the name of the function to call
				args			- optionally, an array of arguments to pass to the function
			This function will BLOCK untuk the server responds with (& returns) the responseText of a call to that PHP function
			
			document.getElementById("dest").innerHtml = get_response_from("Sql-Controls.php", "checkboxes_from_sql", args);
		*/
		
		if(args==undefined) {
			args = new Array();
		}
		
		rq = createRequest();
		if (rq) {
			rq.open("POST", url, false);
			rq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			
			//"checkboxes_from_sql("selected_object_types", "SELECT id, description FROM objecttypes WHERE accessflag >= 1;")
			data = "FUNCTION="+function_name;
			for(var i in args) {
				data += "&ARGUMENTS["+i+"]="+args[i];
			}
			//alert("Sending data:\n"+data);
			rq.send(data); //NOTE: This is an intentionally blocking call.
			//alert("Response:\n"+rq.responseText);
			return rq.responseText;
		}

	}
	
	function async_response_from(url, function_name, args, callback_function) {
		/*  Given:
				url				- the location of a php library that has been 'ajaxified' using 'Ajaxify.php'						
				function_name 	- the name of the function to call
				args			- an array of arguments to pass to the function
				callback_function - the name of a callback function to invoke when the server responds
			This function will return the responseText of a call to that PHP function
			
		e.g. 
			To Call "checkboxes_from_sql("selected_object_types", "SELECT id, description FROM objecttypes WHERE accessflag >= 1;") from Sql-Controls.php:
				var args=new Array("selected_object_types", "SELECT id, description FROM objecttypes WHERE accessflag >= 1;");
				get_response_from("Sql-Controls.php", "checkboxes_from_sql", args, my_callback);
				
				function my_callback(ret) {
					document.getElementById("dest").innerHtml = ret.responseText;
				}
		*/
		var currtime = new Date();
		return_html = "<i>No response from "+url+" for "+function_name+".</i> ["+currtime.getHours()+":"+currtime.getMinutes()+"."+currtime.getSeconds()+"]";
		
		rq = createRequest();
		if(rq) {
			rq.onreadystatechange = function() {
				if(rq.readyState == 4) {							
					callback_function( rq );
				}
			}
			rq.open("POST", url, true);
			rq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
			//"checkboxes_from_sql("selected_object_types", "SELECT id, description FROM objecttypes WHERE accessflag >= 1;")
			data = "FUNCTION="+function_name;
			for(var i in args) {
				data += "&ARGUMENTS["+i+"]="+args[i];
			}
			return_html += "<br />"+data;
			rq.send(data);
		} else {
			alert("Unable to instantiate Ajax.");
		}
		
		return return_html;
	}

	function createRequest() {
		try {
			request = new XMLHttpRequest();
		} catch (tryMS) {
			try {
				request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (otherMS) {
				try {
					request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (failed) {
					request = null;
				}
			}
		}
		
		return request;
	}
	
	function addLoadEvent(func) { 
	  var oldonload = window.onload; 
	  if (typeof window.onload != 'function') { 
		window.onload = func; 
	  } else { 
		window.onload = function() { 
		  if (oldonload) { 
			oldonload(); 
		  } 
		  func(); 
		} 
	  } 
	} 
	
	function addEventHandler(obj, eventName, handler) {				
		if(document.attachEvent) {
			//alert("IE7 Call");
			obj.attachEvent("on"+eventName, handler);
		} else if (document.addEventListener) {
			//alert("Firefox Call");
			obj.addEventListener(eventName, handler, false);
		}
	}
	
	function getActivatedObject(e) {
		var obj;
		if(!e) {
			//Early versions of IE
			obj = window.event.srcElement;
		} else if (e.srcElement) {
			//IE 7+
			obj = e.srcElement;
		} else {
			//Good browsers
			obj = e.target;
		}
		return obj;
	}
	
	function getElementsByName(control_name) {
		try {
			controls = document.getElementsByName(control_name);
		} catch(iesucks) {
			controls = getElementsByName_iefix(control_name);
		}
		
		return controls;
	}
		
	function getElementsByName_iefix(name) {
		 var elem = document.getElementsByTagName("*");
		 var arr = new Array();
		 for(i = 0,iarr = 0; i < elem.length; i++) {
			  att = elem[i].getAttribute("name");
			  if(att == name) {
				   arr[iarr] = elem[i];
				   iarr++;
			  }
		 }
		 return arr;
	}
	
	function getElementsByClassName(clsName){ 
	
		var retVal = new Array(); 
		var elements = document.getElementsByTagName("*"); 
		
		for(var i = 0;i < elements.length;i++){ 
			if(elements[i].className.indexOf(" ") >= 0){ 
				var classes = elements[i].className.split(" "); 
				for(var j = 0;j < classes.length;j++){ 
					if(classes[j] == clsName) {
						retVal.push(elements[i]); 
					}
				} 
			} else if(elements[i].className == clsName) {
				retVal.push(elements[i]); 
			}
		} 
		return retVal; 
	}
	
	function get_elements(attribute_elements, an_optional_existing_data_object) {
		/* Given
			attribute_elements	- a collection of HTML elements, 
			optionally an existing collection of data (to allow for multiple calls)
		returns a JSON object of their data / value pairs. */
		
		if(!an_optional_existing_data_object) {
			var return_data = new Object();
		} else {
			var return_data = an_optional_existing_data_object;
		}
		
		for(var n in attribute_elements) {
			var attribute_control = attribute_elements[n];
			if(attribute_control) {
				//Get the name of the element to store			
				try {
					if(attribute_control.id) {
						var element_name = attribute_control.id;
					} else if(attribute_control.name){
						var element_name = attribute_control.name;
					} else {
						var element_name = "UNKNOWN_ID"; //On the offchance you didn't give the element an id (which you should have!) 
					}
				} catch(err) {
					var element_name = "UNKNOWN_ID"; //On the offchance you didn't give the element an id (which you should have!) 
					continue;
				}
				var element_value = new Object();
				var j = -1;			
				
				//Read the Values
				try {
					if(attribute_control.tagName==undefined) {
						continue;
					} else if(attribute_control.tagName.toLowerCase()=="label") {
						j++;
						element_value[j] = attribute_control.innerHTML;
						
					} else 	if (attribute_control.type=="text") {
						j++;
						element_value[j] = attribute_control.value;

					} else 	if (attribute_control.type=="hidden") {
						j++;
						element_value[j] = attribute_control.value;
												
					} else if(attribute_control.type.substr(0,6) == "select") {
						//Form drop down boxes, need to select the correct option
						try {
							var selected_option = attribute_control.options[attribute_control.selectedIndex]; 
							j++;
							element_value[j] = selected_option.value;
						} catch(err) {
							//If there is no selectedIndex...
							j = -1;
						}
						
					} else if (attribute_control.type=="checkbox") {
						j++;
						element_value[j] = attribute_control.checked;
						
					} else if(attribute_control.type=="radio") {
						controls = getElementsByName(attribute_control.name);
						for (var option =0; option < controls.length; option++) {
							if(controls[option].checked) {
								j++;
								element_value[j] = controls[option].value;
							}
						}
					}
				} catch(err) {
					element_value[j] = err;
				}
				
				//Store as a scalar or as an array, based on the returns...
				if(j==0) {
					element_value[0] = (element_value[0]=='null') ? null : element_value[0];
					return_data[element_name] = element_value[0]; //stored as a single element
				} else if (j >0) {
					for (var n in element_value) {
						element_value[n] = (element_value[n]=='null') ? null : element_value[n];
					}
					return_data[element_name] = element_value; //multiple responses stored as an array
				} else {
					//If no data was read, store a null;
					return_data[element_name] = null;
				}
				
			}
		}
		
		return return_data;
	}
	
	function set_elements(json_row) {
		/* Given an object (json_row) that is a bunch of property/values pairs corresponding to Html controls on the current page,
			this function sets each of the elements on the page to those values 
			e.g. given my_data.element1="value 1"; my_data.something_else="Value 38"
				element1 will be set to "value 1" 
				something_else will be set to "Value 38"
			This function can set text boxes, dropdowns, radiobuttons and checkboxes
		*/
		
		for(var attr_name in json_row) {
			try {
				var element_name = attr_name;
				var element_value = json_row[attr_name]; 
				set_element(element_name, element_value);		
			} catch(IgnoreErr) {}
		}
	}
	
	function set_element(element_name, element_value) {
		if(element_value) {
			var attribute_control = document.getElementById(element_name);
			if(attribute_control) {
			
				if(attribute_control.tagName.toLowerCase()=="label") {
					attribute_control.innerHTML = element_value;
					
				} else 	if (attribute_control.type=="text" || attribute_control.type=="hidden") {
					attribute_control.value = element_value;
				} else if(attribute_control.type.substr(0,6) == "select") {
					//Form drop down boxes, need to select the correct option
					element_value = element_value.trim();
					for (var option =0; option < attribute_control.options.length; option++) {
						if(attribute_control[option].value == element_value) {
							attribute_control.selectedIndex = option;
						}
					}
				} else if (attribute_control.type=="radio") {
					try {
						controls = document.getElementsByName(attribute_control.name);
					} catch(iesucks) {
						controls = getElementsByName_iefix(attribute_control.name);
					}
					
					for (var option =0; option < controls.length; option++) {
						if(controls[option].value == element_value) {
							controls[option].checked=true;
						}
					}
				} else if (attribute_control.type=="checkbox") {
					attribute_control.checked = (element_value == attribute_control.value);
				}
			}
		}
	}
		
	function clear_elements(attribute_elements) {
		for(var i in attribute_elements) {
			if(attribute_elements[i].type=="select") {
				attribute_elements[i].selectedIndex = 1;
			} else {
				attribute_elements[i].value="";
			}
		}
	}
	
	function cancel(default_page) {
		if(window.history.length > 1) {
			window.history.back();
		} else {
			window.location = default_page;
		}
	}
			
	function set_default_value(element, default_value) {
		for(var n in element.options) {
			opt = element.options[n];
			if(opt.value == default_value) {
				element.selectedIndex = n;
			}
		}
	}
	
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}

	function getURLParameter( param_name ) {
		param_name = param_name.replace(/[\[]/, "\\\[").replace(/[\]]/,"\\\]");
		
		var regexS = "[\\?&]"+param_name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if ( results == null ) {
			return "";
		} else {
			return results[1];
		}
	}
	
	function set_dropdown_element(control, value) {
		if(control) {
			if(control.options) {
				for(var n=0; n<control.options.length; n++) {
					if(control.options[n].value==value) {
						control.selectedIndex = n;
						return;
					}
				}				
				control.selectedIndex = 0;
			}
		}
	}
			
	function numeric_options(from_val, to_val, skip_val) {
		//Creates a series of <OPTION value='x'>X</OPTION> from x to y, every skip_val. (If skip val is omitted, its 1)
		
		if(skip_val==undefined) {
			skip_val=1;
		}
		
		ret = "";
		for(var n=from_val; n<=to_val; n = n+skip_val) {
			ret += "<OPTION value='"+n.toString()+"'>"+n.toString()+"</OPTION>";
		}		
		return ret;
	}
			
