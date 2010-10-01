	function DynamicTable(table_element_id) {
		/* Creates and maintains data in table form.
		
			tbl.new_row_function			- specifies a function that returns HTML for a new, empty row of the table.
			tbl.populate_data(dataobject)	- Given a dataobject with properties that match the base field names specified in new_row_function, sets the values approrpiately.
			dataobject = tbl.read_data()	- reads data entered on the passed table.
			
			tbl.rows()						- returns the number of rows currently in the table
		*/
		
		var self = this;
		self.table_element = document.getElementById(table_element_id);
		self.table_element.innerHTML = "";
		
		self.has_edit_buttons = false;		
		self.remove_hides_from_column = 2;
		self.add_row_function = this.add_a_row;
		self.remove_row_function = this.remove_a_row;
		self.element_changed_function = this.on_element_change;
		self.on_add_row_function = null;	//called after a new row is added - if you want addtl functionality
		
		self.rows = function() {
			return this.table_element.rows.length;
		}
		self.columns = function() {
			return this.table_element.rows[0].cells.length;
		}
	}
	
	DynamicTable.prototype.set_header = function(header_html) {
		this.header_html = header_html;
		var hdr_row = this.table_element.insertRow(0);
		hdr_row.innerHTML = header_html;
		hdr_row.className='HeaderRow';
	}
	
	DynamicTable.prototype.clear_table = function() {
		this.table_element.innerHTML = "";
		this.set_header(this.header_html);
		if(this.has_edit_buttons) {
			this.add_editing_buttons();
		}
	}
	
	DynamicTable.prototype.populate_data = function(table_data) {
	
		for(var row_num=0; row_num<table_data.length; row_num++) {
			//Add a new row to the table			
			var new_row = this.add_row();
					
			//Set that data element (if a matching fieldname exists...)
			var data_row = table_data[row_num];
			
			for(var document_element_name in data_row) {
				var data_valu = data_row[document_element_name];				
				set_element(document_element_name+"_"+(row_num+1), data_valu);
				var attr_control = document.getElementById(document_element_name+"_"+(row_num+1));
				if(attr_control) {
					attr_control.setAttribute('initial_value', data_valu);
				}
			}
		}
		
	}

	DynamicTable.prototype.set_value = function(row, column, data_valu) {
			new Cell(this.table_element, row, column).set_value(data_valu);
	}

	DynamicTable.prototype.get_cell_containing = function(element) {
		//Given any control element on the form, returns a cell containing that element.
		
		//Get the table row
		var tr = element;
		while(tr!=null && tr.tagName.toLowerCase()!='tr') {
			tr= (tr.parentNode==undefined) ? null : tr.parentNode;
		}
		
		for(var cell_num=0; cell_num < tr.cells.length; cell_num++) {
			var test_cell = new Cell(this.table_element, tr, cell_num);
			var cell_contents = test_cell.cell_contents();
			for(var nel=0; nel<cell_contents.length; nel++) {
				if(element == cell_contents[nel]) {
					return test_cell;
				}
			}
		}			
		return null;
	}
	
	DynamicTable.prototype.add_a_row = function(evt_args) {		
		var new_row = this.add_row();
		new_row.className='dirty';
		new_row.setAttribute('action', 'add');
		new_row.setAttribute('initial_action', 'add');
	
		//Set empty disabled text boxes to 'NEW'
		for(var ncell=0; ncell<new_row.cells.length; ncell++) {
			var the_cell = new Cell(this.table_element, new_row, ncell);
			var elems = the_cell.cell_contents();
			for(var elem=0; elem<elems.length; elem++) {
				if(elems[elem].tagName.toLowerCase()=='input' && elems[elem].type.toLowerCase()=='text' && elems[elem].value=='' && elems[elem].disabled) {
					elems[elem].value='NEW';
				}
			}
		}
		
		var row_num = new_row.getAttribute("row_num");
		if(this.on_add_row_function) {
			this.on_add_row_function(row_num);
		}
	}
	
	DynamicTable.prototype.remove_a_row = function(evt_args) {
		var row_button = getActivatedObject(evt_args);	
		
		var row_number = row_button.getAttribute('row_number');
		var row_cell = this.get_cell_containing(row_button);
		var row_num = row_cell.row_number;
		
		if(row_button.value=='-') {
			var removed_row = this.remove_row(row_num);			
			//Create an 'undo remove' button
			row_button.value='restore';
		} else {
			//Restore the row
			var removed_row = this.restore_row(row_num);			
			row_button.value='-';
		
		}
	}
			
	DynamicTable.prototype.add_editing_buttons = function() {
		var button_guid = this.table_element.id;
		
		//Insert a column for all existing rows
		for(var row_num=0; row_num<this.table_element.rows.length; row_num++) {
			
			var existing_row = this.table_element.rows[row_num];
			var button_cell = existing_row.insertCell(0);
			
			if(row_num==0) {
				button_cell.innerHTML = "<INPUT id='"+button_guid+"_add_button' type='button' value='+' class='edited_attribute add_row_button'></INPUT>";
				var a_button = document.getElementById(button_guid+"_add_button");
				addEventHandler(a_button, "click", this.add_row_function.bind(this));
			} else {
				button_cell.innerHTML = "<INPUT id='"+button_guid+"_remove_button_"+row_num+"' type='button' value='-' row_number='"+row_num+"' class='edited_attribute remove_row_button'></INPUT>";
				var a_button = document.getElementById(button_guid+"_remove_button_"+row_num);
				addEventHandler(a_button, "click", this.remove_row_function.bind(this));
			}
		}

		//set a flag for when a new row is inserted...
		this.has_edit_buttons = true; 		
	}

	DynamicTable.prototype.add_row = function( optional_insertion_point ) {

		var row_num = (optional_insertion_point==undefined) ? this.table_element.rows.length : optional_insertion_point;
		var new_row = this.table_element.insertRow(row_num);
		new_row.innerHTML = this.new_row_function();
		new_row.setAttribute("row_num", row_num);
		
		if(this.has_edit_buttons) {
			var button_guid = this.table_element.id;
			var button_cell = new_row.insertCell(0);			

			button_cell.innerHTML = "<INPUT id='"+button_guid+"_remove_button_"+row_num+"' type='button' value='-' row_number='"+row_num+"' class='edited_attribute'></INPUT>";
			var a_button = document.getElementById(button_guid+"_remove_button_"+row_num);
			addEventHandler(a_button, "click", this.remove_a_row.bind(this));
		}
				
		//Wire up events to each control element in the row, so that changes can be monitored.
		for(var col_num=0; col_num < new_row.cells.length; col_num++) {
			var the_cell = new Cell(this.table_element, new_row, col_num);
			var contents = the_cell.cell_contents();
			for(var el=0; el< contents.length; el++) {
				var an_element = contents[el];
				this.define_table_element(an_element, row_num);
			}			
		}
		
		return new_row;
	}
	
	DynamicTable.prototype.define_table_element = function(an_element, row_num) {
	
		//Rename a named child control with and id to id_rownumber.
		if(an_element.hasAttribute('id')) {
			an_element.parentNode.setAttribute('field_name', an_element.id);		//Store the name of the element in the <TD field_name='my_field'> attribute
			an_element.id = an_element.id+"_"+row_num.toString();	//Rename the passed control to disambugate different lines
		}

		addEventHandler(an_element, "blur", this.element_changed_function.bind(this))
		addEventHandler(an_element, "click", this.element_changed_function.bind(this))
		addEventHandler(an_element, "change", this.element_changed_function.bind(this)) 
		addEventHandler(an_element, "keyup", this.element_changed_function.bind(this)) 
		
		//Recurse
		if(an_element.hasChildNodes()) {
			var num_els = an_element.childNodes.length;
			for(var n=0; n<num_els; n++) {
				var child_node = an_element.childNodes[n];				
				try {
					if(child_node.hasAttribute('id')) {
						this.define_table_element(child_node, row_num);
					}
				} catch(IgnoreErr) {}
			}
		}
	}
	
	DynamicTable.prototype.on_element_change = function(evtargs) {
	
		var the_control = getActivatedObject(evtargs);		
		if(the_control.hasAttribute('initial_value')) {
			var initial_value = the_control.getAttribute('initial_value');
			var current_value = the_control.value;
				
			the_control.className= (initial_value == current_value) ? '':'dirty';
			this.update_tr_node(the_control.parentNode);
		}
	}
	
	DynamicTable.prototype.update_tr_node = function(cell) {
		//Given an element within a table row, if ANY element on the table row is in a dirty state, the table row itself gets marked for update.		
		var tr_row = cell.parentNode;
		if(tr_row==null) {
			return;
		}
		
		if(tr_row.hasAttribute('initial_action')) {
			if(tr_row.getAttribute('initial_action')=='add') {
				return; //no update needed...
			}
		}
		
		for(var cell_num=0; cell_num<tr_row.cells.length; cell_num++) {
			var child_cell = new Cell(this.table_element, tr_row, cell_num);
			if(child_cell.is_dirty()) {				
				tr_row.setAttribute('action','update');
				return;
			}
		}
		tr_row.setAttribute('action', '');
		
	}

	DynamicTable.prototype.restore_row = function(row_num) {
	
		var existing_rows = this.table_element.rows.length;
		if(row_num > existing_rows) {
			return; //Probably sshould throw a 'Row does not exist in table!' error - but this is less drastic :)
		}
				
		var removed_row = this.table_element.rows[row_num];		
		removed_row.setAttribute('action', '');
		if(removed_row) {
			removed_row.className='';
			for(var cell=this.remove_hides_from_column; cell<removed_row.cells.length; cell++) {
				this.unhide_contents(row_num, cell);					
			}
		}
		this.update_tr_node(removed_row.cells[0]);
		
		if(this.on_restore_row_function) {
			this.on_remove_row_function(row_num);
		}

		return removed_row;
	}
	
	DynamicTable.prototype.remove_row = function(row_num) {

		var existing_rows = this.table_element.rows.length;
		if(row_num > existing_rows) {
			return; //Probably sshould throw a 'Row does not exist in table!' error - but this is less drastic :)
		}
				
		var rem_row = this.table_element.rows[row_num];		
		if(rem_row.hasAttribute('initial_action')) {
			if(rem_row.getAttribute('initial_action')=='add') {
				//simply remove the row - this never hits the database
				this.table_element.deleteRow(row_num);
				return null;
			} 
		}
		
		//Remove Row
		rem_row.className='dirty';
		for(var cell=this.remove_hides_from_column; cell<rem_row.cells.length; cell++) {
			this.hide_contents(row_num, cell);					
		}
		
		rem_row.setAttribute('action', 'remove');
		if(this.on_remove_row_function) {
			this.on_remove_row_function(row_num);
		}

		return rem_row;	
	}
	
	DynamicTable.prototype.hide_contents = function(row_num, col_num) {	
		var the_cell = new Cell(this.table_element, row_num, col_num);
		the_cell.hide_contents(true);
	}

	DynamicTable.prototype.unhide_contents = function(row_num, col_num) {	
		var the_cell = new Cell(this.table_element, row_num, col_num);
		the_cell.hide_contents(false);
	}

	DynamicTable.prototype.set_disabled_state_of_contents = function(row_num, col_num, set_to_state) {
		var the_cell = new Cell(this.table_element, row_num, col_num);
		the_cell.disable_contents(set_to_state);
	}
	
	DynamicTable.prototype.get_cell = function(row_num, col_num) {
		if(row_num > this.table_element.rows.length) {
			return;
		}		
		var row = this.table_element.rows[row_num];
		
		if(col_num > row.cells.length) {
			return;
		}
		return row.cells[col_num];
	}
		
	DynamicTable.prototype.read_data = function() {
		//Returns an object containing data from all rows in the table.
		var ret_object = new Object();
		ret_object.rows = new Array();
		
		for(var row_num=1; row_num < this.table_element.rows.length; row_num++) {
			//For each row of data (skip the header!), read the values of each control...
			var the_row = this.table_element.rows[row_num];
			var data_row = new Object();
			
			//if(this.has_edit_buttons) {
				var tr_node = this.table_element.rows[row_num];
				data_row.action = tr_node.getAttribute("action");
			//}
			
			for(var col_num = (this.has_edit_buttons)?1:0 ; col_num < the_row.cells.length; col_num++) {
				var my_cell = new Cell(this.table_element, the_row, col_num);
				if(my_cell) {
					my_cell.read_values(data_row);
				}
			}
			
			ret_object.rows.push(data_row);		
		}
		
		return ret_object;
	}

	DynamicTable.prototype.get_cell = function(row, column) {
		return new Cell(this.table_element, row, column);
	}	
	
	function Cell(table_object, the_row, column) {

		//An object that can retrieve a particular cell in a passed table
		var self = this;
		self.table = table_object;
		self.row = null;
		self.row_number = -1;
		
		//Define the row & row_number
		if(typeof(the_row) == 'number') {		
			self.row_number = the_row;
			self.row = self.table.rows[the_row];			
		} else if (typeof(the_row) == 'string') {
			self.row_number = parseInt(the_row);
			self.row = self.table.rows[parseInt(the_row)];
		} else {
			for(var row_num=0; row_num < self.table.rows.length; row_num++) {
				if(self.table.rows[row_num]==the_row) {
					self.row_number = row_num;
				}
			}			
			self.row = the_row;			
		}
	
		//Define the_cell, field_name, and column_number.
		if(typeof(column)=='number') {
			//Here the column number has been passed
			self.the_cell = self.row.cells[column];
			self.field_name = self.the_cell.getAttribute('field_name');
			self.column_number = column;
			
		} else if (typeof(column)=='string') {
			//Here, the name of the field has been passed
			self.field_name = column;
			self.column_number = -1;			
			
			for(var cell_num=0; cell_num < self.row.cells.length; cell_num++) {
				var cell_column_name = self.row.cells[cell_num].getAttribute('field_name');
				if(cell_column_name) {				
					if(cell_column_name.toLowerCase()==column.toLowerCase()) {
						self.the_cell = this.row.cells[cell_num];
						self.column_number = cell_num;
						break;
					}
				}
			}
			
		} else {
			//Here the cell object itself has been passed
			self.the_cell = column;
			self.field_name = column.getAttribute('field_name');
			for(var cell_num=0; cell_num <= this.row().cells.length; cell_num++) {
				if(self.field_name == this.row().cells[cell_num].getAttribute('field_name')) {
					self.column_number = cell_num;
					break;
				}
			}
		}
		
	}
	
	Cell.prototype.disable_contents = function(state) {
		var elems = this.cell_contents();
		for(var el in elems) {
			elems[el].disabled = state;
		}
	}
		
	Cell.prototype.hide_contents = function(state) {
		var elems = this.cell_contents();
		for(var el in elems) {
			elems[el].style.display = (state) ? 'none':'';
		}
	}
		
	Cell.prototype.cell_contents = function() {
		var ret_array = new Array();
		var cell = this.the_cell;
		if(cell.childNodes) {
			for(var n=0; n<cell.childNodes.length; n++) {
				try {
					var elem = cell.childNodes[n];
					if(elem.hasAttribute('id')) {
						ret_array.push(elem);
					}
				} catch(err) {}
			}
		}
		return ret_array;
	}
		
	Cell.prototype.set_value = function(data_valu) {		
		if(this.the_cell == undefined)  { 
			//Ensure that you actually have actually found this column!
			throw "column not found - " + this.field_name;
		}			
		
		var document_element_name = this.field_name+ "_"+this.row_number;
		set_element(document_element_name, data_valu);
		
		//Track if the value has changed by adding a 'dirty' class attribite to any element that differs from its initial_value
		if(document.getElementById(document_element_name)) {
			document.getElementById(document_element_name).setAttribute('initial_value', data_valu);
		}
	}

	Cell.prototype.read_values = function(data_object) {
		//Returns the value of controls in the cell
		var prelim_data = get_elements(this.cell_contents());
		for(var element_name in prelim_data) {			
			var el = element_name;
			var chop_at = element_name.lastIndexOf("_");
			element_name = element_name.substr(0, chop_at);
			data_object[element_name] = prelim_data[el];
		}
		
	}
		
	Cell.prototype.is_dirty = function() {
		var elements = this.cell_contents();
		for(var el in elements) {
			if(elements[el].className=='dirty') {
				return true;
			}
		}
		return false;
	}
	
	Function.prototype.bind = function(obj) {
	var method = this,
		temp = function() {
			return method.apply(obj, arguments);
		};
		return temp;
	}
	