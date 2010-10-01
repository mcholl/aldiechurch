	
	function Object_Selector(object_type_id, object_type_name, div_element_selector) {
		//Represents a set of checkboxes for each object type. (i.e. The List of VPN checkboxes, the list of PROXY checkboxes, etc...
		var self = this;
		self.object_type_id = object_type_id;
		self.object_type_name = object_type_name;
		self.div_element_selector = div_element_selector;
		
		this.tie_to_checkbox = function(object_type_checkbox) {
			self.object_type_checkbox = object_type_checkbox;
			addEventHandler(object_type_checkbox, "click", self.toggle.bind(self));	//Link the Click event so the obj_lister will either appear or disappear when you select it.
		}
		
		this.toggle = function() {
			//alert("Calling toggle for "+self.object_type_name+" checkboxes");			
			if (self.object_type_checkbox.checked) {
				self.make(object_type_id, object_type_name, div_element_selector);
			} else {
				self.destroy(div_element_selector);
			}
		}

	}				

	Object_Selector.prototype.make = function(object_type_id, object_type_name, div_element_selector) {
		//Given the id & name of a type of object (e.g. 1, VPNs), creates a placeholder section which will then be modified w/a call...
		try {
			//alert("Creating objects box for "+object_type_name);
			
			html = "<FIELDSET class='assignable_object' id='object_type~"+object_type_id+"~checkboxes'>\n";
			html += "	<LEGEND>"+object_type_name+"</LEGEND>\n";
			html += "	<INPUT name='toggle_"+object_type_name+"' type='button' value='Toggle all "+object_type_name+"(s)' onclick=\"select_all_in_section('"+object_type_name+"');\" /><br /><br />";
			html += "	<DIV id='"+object_type_name+"' />\n";
			html += "</FIELDSET>\n";					
			
			//div_element.innerHTML = html;
			placeholder = document.getElementById(div_element_selector);
			placeholder.innerHTML = html;

			type_id = object_type_id.substr(object_type_id.indexOf("-")+1);
			//var args=new Array(object_type_name, "SELECT id, description, adminrole_object_id FROM objects WHERE objecttype_id= "+ type_id +" AND deletetime=0;");
			//document.getElementById(object_type_name).innerHTML = get_response_from("Sql-Controls.php", "checkboxes_from_sql", args);
			chkboxes_html = get_response_from("ObjectAccess.php", "object_checkboxes", new Array(object_type_name, type_id));
			document.getElementById(object_type_name).innerHTML = chkboxes_html;
			select_all_in_section(object_type_name, false);

			//Add the object_changed event to each object checbox.
			try {
				obj_boxes = document.getElementsByName(object_type_name);
			} catch(iesucks) {
				obj_boxes = getElementsByName_iefix(object_type_name);
			}
			for(var n=0; n<obj_boxes.length; n++) {
				obj_box = obj_boxes[n];
				if(obj_box) {
					addEventHandler(obj_box, "click", object_changed);
				}
			}

			
			//alert("<DIV id='"+placeholder.id+"' /> is now:\n"+placeholder.innerHTML);
			
		} catch(e) {
			alert(e);
		}
	}

	Object_Selector.prototype.destroy = function(div_element_selector) {
		//alert("Clearing the selections.");
		try {
			placeholder = document.getElementById(div_element_selector);
			placeholder.innerHTML = "";
		} catch(e) {
			alert(e);
		}
	}
	
	Object_Selector.prototype.disable = function(id_array, state, hint) {
		/* Given 
			(id_array)		an array of ids that correspond to values in this section, 
			(state)			a boolean - true or false,
		this function will set all checkboxes corresponding to the passed id values to the state presented.
			AND it will disable the checkbox.  This is for when anotherthing (like a group) is overriding control*/
		
		var control_name = this.object_type_name;
		try {
			obj_boxes = document.getElementsByName(control_name);
		} catch(iesucks) {
			obj_boxes = getElementsByName_iefix(control_name);
		}
		
		for(var i in obj_boxes) { 
			var chkbox = obj_boxes[i];
			if (inArray(chkbox.value, id_array)) {
				chkbox.checked = state;
				chkbox.disabled = true;
				if(hint!=undefined) { 
					//chkbox.setAttribute("hint", hint);
					chkbox.parentNode.setAttribute("hint", hint); //Because the label surrounds the checkbox, we want the hint on the label...
				}
			}
		};				
	}
	
	Object_Selector.prototype.set = function(id_array, state) {
		/* Given 
			(id_array)		an array of ids that correspond to values in this section, 
			(state)			a boolean - true or false,
		this function will set all checkboxes corresponding to the passed id values to the state presented. */
		
		var control_name = this.object_type_name;
		try {
			obj_boxes = document.getElementsByName(control_name);
		} catch(iesucks) {
			obj_boxes = getElementsByName_iefix(control_name);
		}
		
		for(var i in obj_boxes) { 
			var chkbox = obj_boxes[i];
			if (inArray(chkbox.value, id_array)) {
				chkbox.checked = state;
			}
		};				
	}

	Object_Selector.prototype.clear = function() {
		//Unchecks all of the boxes
		var control_name = this.object_type_name;
		try {
			obj_boxes = document.getElementsByName(control_name);
		} catch(iesucks) {
			obj_boxes = getElementsByName_iefix(control_name);
		}
		
		for(var i in obj_boxes) { 
			obj_boxes[i].checked = false;
		};		
		
	}
	
/* So, why are destroy & make prototypes instead of inner functions like tie_to_checkbox & toggle?
  Quick Answer: It cuts down on memory use in the client.
  Longer answer: Private methods duplicate the entire set of code with each instance of the class. 
				By using prototypes, the code is accessible to the Object without being a member of
				each object instantiated by this class. 'Self' (and 'this') are not as accessible
				when they are prototyped. I say not *as* accessible, because there is a this - but
				this always refers to the calling object, which when toggle is triggered from the
				onClick of the object-type-checkbox is the source element referring to the CHECKBOX
				(not this object like seemingly every other language I've ever used!) and self, being
				globally accessible (why???) ends up retaining its last value, namely the last checbox
				instantiated.

	// Object_Selector.prototype.toggle = function() {
		// //NOTE: this = the calling object (i.e. the checkbox) NOT the current object, as I would have assumed...
		// alert("Calling toggle for ");
		// alert(this.id); //= 'selected_object_types~X', for instance...
		// alert(self.object_type_id);
		// object_type_checkbox = this;

		// if (object_type_checkbox.checked) {
			// make(object_type_checkbox);
		// } else {
			// destroy(object_type_checkbox);
		// }
	// }
*/

	function display_tooltip(evt) {
	
		hint = evt.getAttribute("hint");		
		set_debug_message(hint);
		//window.status = hint;		
	}
	function hide_tooltip() {
		window.status = "";
	}

	function object_changed(e) {
		//This event is fired whenever the admin changes the state of a specific object.  
					
		//Provide visual feedback that the item has been changed (This will only be cleared when the data is saved, cancelled, or the user is changed.)
		chkbox = this;
		lbl = associated_label(chkbox);		
				
		//Do this by adding a class of 'dirty'
		if(chkbox.className == 'dirty') {
			chkbox.className = '';
			lbl.className = '';
		} else {
			chkbox.className = 'dirty';
			lbl.className = 'dirty';
		}
		
		//chkbox.setAttribute("className", "dirty"); 
		//lbl.setAttribute("className", "dirty"); 
	}
	
	function associated_label(checkbox_element) {
		return document.getElementById("label~"+checkbox_element.id);		
	}

	function clear_dirty_marks() {
		var dirty_checkboxes = getElementsByClassName('dirty');
		for(var n=0; n<dirty_checkboxes.length; n++) {
			dirty_checkboxes[n].className='';
		}
	}

	function inArray(needle, haystack_array) {
		//Returns true if needle is an element in the haystack array.
		for(var n in haystack_array) {
			if(haystack_array[n] == needle) {
				return true;
			}
		}
		
		return false;
		
	}

	function select_all_in_section(object_type_name, set_to_state) {
		//alert('Selecting all '+object_type_name+' checkboxes.');
		try {
			obj_boxes = document.getElementsByName(object_type_name);
		} catch(iesucks) {
			obj_boxes = getElementsByName_iefix(object_type_name);
		}
			
		//Determine if you are currently in an 'all checked' or 'all unchecked' state.  
		//Actually any *NOT* checked means in an unchecked state, therefore the button will act as a SELECT ALL
		if(set_to_state == undefined) {
			chk_state = true;
			for(var i in obj_boxes) { 
				if(typeof(obj_boxes[i]) == "object") {
					if (!obj_boxes[i].checked) {							
						chk_state = false;						
						break;
					}
				}
			};			
			set_to_state = !chk_state;
		}

		//Now, iterate through each of the boxes and actually set....
		for(var i in obj_boxes) { 
			if (obj_boxes[i].checked != set_to_state) {
				if(!obj_boxes[i].disabled) {
					obj_boxes[i].checked = set_to_state;
					
					dirty_state = (obj_boxes[i].className=='dirty') ? '':'dirty';
					
					obj_boxes[i].className = dirty_state;
					lbl = associated_label(obj_boxes[i]);
					if(lbl) {lbl.className = dirty_state;}
				}
			}
		};		
		//This would simply invert
		//for(var i in obj_boxes) { obj_boxes[i].checked = !obj_boxes[i].checked;};	
		
		try {
			btn = document.getElementsByName("toggle_"+object_type_name)[0];
		} catch(iesucks) {
			btns = getElementsByName_iefix("toggle_"+object_type_name);
			btn = btns[0];
		}
		btn.value = (!set_to_state) ? "Select all " : "De-select all ";		
		btn.value += object_type_name + "("+get_plural(object_type_name.substr(object_type_name.length-1))+")";
	}
	
	function get_plural(last_letter) {
		if (last_letter.toLowerCase()=="x" || last_letter.toLowerCase()=="s") {
			return "es";
		} else if (last_letter.toLowerCase()=="y") {
			return "ies";
		} else {
			return "s";
		}

	}

Function.prototype.bind = function(obj) {
	var method = this,
		temp = function() {
			return method.apply(obj, arguments);
		};
		return temp;
}