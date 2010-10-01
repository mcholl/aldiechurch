		function do_url_startup_action() {
			default_action = getURLParameter("action");
			if(default_action == 'new') {
				start_new_entry_mode();
			} else if (default_action=='edit') {
				start_edit_mode();
			} else {
				end_edit_mode();
			}
		}
		
		function populate_group_selector() {
		  window.group_selector=new dhtmlXCombo("group_selector", "add_selected_group", 200);
		  group_selector.loadXML("Users.php?FUNCTION=show_groups");
		  group_selector.enableFilteringMode(true);
		}
		
		function populate_user_control() {
		  window.selected_ug=new dhtmlXCombo("ug_suggestions", "selected_ug", 200);
		  selected_ug.loadXML("Users.php?FUNCTION=show_users");
		  selected_ug.enableFilteringMode(true); //, "Users.php?FUNCTION=show_users", true);
		  selected_ug.attachEvent("onSelectionChange", on_user_change);
		}
		
		function clear_validations() {
			if(window.vf) {
				window.vf.remove_warnings();
			}
		}
						
		function start_new_entry_mode() {
			clear_existing_data();
			start_edit_mode();			
		}

		function start_edit_mode() {
			buttons_visibility("edit");
			enable_extended_attributes_fields("edited_attribute", true);
			set_validations();
		}
		
		function end_edit_mode() {
			buttons_visibility("normal");
			enable_extended_attributes_fields("edited_attribute", false);
			clear_validations();
		}
		
		function buttons_visibility(mode) {

			edit_mode_rule = get_css_rule('.editMode');
			edit_mode_rule.style.display= (mode=="edit") ? '':'none';
			
			normal_mode_rule = get_css_rule('.normalMode');				
			normal_mode_rule.style.display = (mode=="edit") ? 'none':'';

		}			
		
		function enable_extended_attributes_fields(class_name, set_to_state) {
					
			var attr_fields = document.getElementsByClassName(class_name);
			for(var n in attr_fields) {
				var attr = attr_fields[n];
				attr.disabled = !set_to_state;

				if(attr.tagName) {					
					if(attr.tagName.toLowerCase()=='input' && attr.hasAttribute('type') && attr.getAttribute('type')=='button') {
						attr.style.display = (set_to_state) ? '':'none';
					}
				}
			}
		}

				
		$.fn.collapse = function() {
			return this.each(function() {
				$(this).find("legend")
					.addClass('collapsible')
					.click(function() {
						if($(this).parent().hasClass('collapsed')) {
							$(this).parent()
								.removeClass('collapsed')
								.addClass('collapsible');
						}
						
						$(this).removeClass('collapsed');
						
						$(this).parent().children().not('legend')
							.toggle("slow", function() {
								if($(this).is(":visible")) {
									$(this).parent().find("legend").addClass('collapsible');
								} else {
									$(this).parent().addClass('collapsed')
										.find("legend").addClass('collapsed');
								}
							});
						});
					});
		}