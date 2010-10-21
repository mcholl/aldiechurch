<?php
	/*  This library turns any php function library into stuff that can be easily called from an Ajax / Javascript call.
	SETUP:
		To use this library, insert the following into your php library:
		include("Ajaxify.php");
		
	USE:
		Now, when you want to make a call to anything in the library, simply POST a request to your PHP library, 
		along with two variables - FUNCTION (the name of the function to call) and ARGUMENTS (an array of function args.)
		
		For an example, look at the form below that shows how to call this from an <HTML><FORM>
		
	NOTE: If you just want to see & test the functions in your library, call the page with ?Test.  That will display a form listing all your functions	
	*/
	
	if (isset($_GET['FUNCTION'])) {
		$requested_function = $_GET['FUNCTION'];
		call_user_func($requested_function);
	}
	

	if (isset($_POST['FUNCTION'])) {  						
		#Find out what function is being requested
		$requested_function = $_POST['FUNCTION']; 	#Could be 'radio_buttons_from_sql', 'input_from_sql', 'checkboxes_from_sql', etc....
		
		if(isset( $_POST['ARGUMENTS'])) {
			$arguments = $_POST['ARGUMENTS'];			#An array containing the arguments to pass to $_POST('FUNCTION') *in the correct order*
		} else {
			$arguments = null;
		}
		
		/*
		echo "Calling =>$requested_function<=";
		foreach ($arguments as $arg) {
			echo "($arg)";
		} 
		*/
		call_user_func_array($requested_function, $arguments);
		
	} elseif (isset($_GET['Test'])) {
	
	
		//Ideally, I'd dynamically generate each function - but that's for another day...
		?> 
		<HTML>
			<HEAD>
				<TITLE>Sql-Objects.php Library</TITLE>
				<STYLE type="text/css">
					fieldset label.l {
						width: 240px;
						display: block;
						float: left;
					}
					
					fieldset.Test SELECT, INPUT {
						width: 500px;
						display: block;
					}
															
				</STYLE>
			</HEAD>
			<BODY>
				<FIELDSET class="function_list">
					<LEGEND>User Defined Functions</LEGEND>
					<?php
						$all_funcs = get_defined_functions();
						$argstring = "eventually I will read the arguments and display them here.";
						foreach($all_funcs['user'] as $func) {							
							echo "<LI class=\"funcname\">$func($argstring)</LI>\n";
						}
					?>
				</FIELDSET>
				
				<FORM name="Test" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
				
					<FIELDSET class="Test">
						<LEGEND>Test Function Call</LEGEND>
						<LABEL for="FUNCTION" class="l">Function:</LABEL>
						<!--<INPUT type="text" name="FUNCTION" id="FUNCTION" value="checkboxes_from_sql" />-->
						<SELECT class="allfuncs" id="FUNCTION" name="FUNCTION">
						<?php
							foreach($all_funcs['user'] as $func) {
								echo "<OPTION value='$func' >$func</OPTION>\n";
							}
						?>
						</SELECT>
						
						<?php $n = 0;
						for($n = 0; $n<10; $n++) { ?>
								<LABEL for="controlname" class="l">Argument <?php echo $n+1 ?>:</LABEL>
								<INPUT type="text" name="ARGUMENTS[<?php echo $n ?>]" id="controlname" value="" />
						<?php } ?>
						
					</FIELDSET>
					
					<INPUT class="submit" type="submit" name="submit" value="submit" />
				</FORM>
			</BODY>
		</HTML>
		<?php
	}
	
?>