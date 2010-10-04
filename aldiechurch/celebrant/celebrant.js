		function set_participants_by_role( selected_role ) {
		
			//Get a new body (in order to get the selction choices to be valid
			var new_body = get_response_from( 
				"Celebrant.php", 
				"get_editbody_editbyrole", 
				new Array(selected_role+'')
			);			
			$("DIV#body_area").html(new_body);
			
			//Populate the empty form with data from the database
			var date_ids = new Array();
			var els = $("div.date_row").get();
			for(var el in els) {
				date_ids.push( $(els[el]).attr('date_id') );
			}
		
			var date_data = JSON.stringify(date_ids);
			var role_id = $("#role_id").val();
			var res= get_response_from("Celebrant.php", 'get_participants_by_role', new Array(role_id, date_data));

			try {
				var data = JSON.parse(res);
			} catch(e) {
				alert(res);
			}
			
			for(var ro=0; ro < data.rows.length; ro++) {
				try {
					var rd = data.rows[ro];
					var sel = "div.date_row[date_id='" + rd.date_id +"'] div.answer ";
					$(sel+" SELECT").val( rd.p_id );
					$(sel+" LABEL").text( rd.name );
				} catch(exception) {
					Console.WriteLine(exception)
				}
			}
		}
		
		function set_participants_by_date( selected_date ) {			
			
			//Populate the empty form with data from the database
			var role_ids = new Array();
			var els = $("div.job_row").get();
			for(var el in els) {
				role_ids.push( $(els[el]).attr('role_id') );
			}
		
			var role_data = JSON.stringify(role_ids);
			var date_id = $("#service_date").val();
			var res= get_response_from('Celebrant.php', 'get_participants_by_date', new Array(date_id, role_data));
			//alert(res);
			try {
				var data = JSON.parse(res);
			} catch(e) {
				alert(res);
			}
			
			for(var ro=0; ro < data.rows.length; ro++) {
				try {
					var rd = data.rows[ro];
					var sel = "div.job_row[role_id='" + rd.role_id +"'] div.answer ";
					$(sel+" SELECT").val( rd.p_id );
					$(sel+" LABEL").text( rd.name );
				} catch(exception) {
					Console.WriteLine(exception)
				}
			}
		}
		
