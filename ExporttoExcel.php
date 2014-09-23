<?php
/**
 * Plugin Name: ExporttoExcel
 * Plugin URI: 
 * Description: This plugin will allow you to export all the table content into the excel file.
 * Version:  1.0
 * Author: Ujjaval
 * Author URI: 
 * License: Open Source
 */
ob_clean();
ob_start();
/* Runs when plugin is activated */
register_activation_hook(__FILE__,'ExporttoExcel_install'); 
/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'ExporttoExcel_remove' );
function ExporttoExcel_install() {
	/* Creates new database field */
	/* add_option("hello_world_data", 'Default', '', 'yes'); */
	global $wpdb;
	$sql="CREATE TABLE wp_formdetailsexportor(table_id INT NULL  AUTO_INCREMENT,PRIMARY KEY(table_id),selected_tablename VARCHAR(30))";
	$wpdb->query($sql);
}
function ExporttoExcel_remove() {
	/* Deletes the database field */
	global $wpdb;
	$sql="DROP TABLE wp_formdetailsexportor";
	$wpdb->query($sql);
}
add_action('admin_menu', 'ExporttoExcel_admin_menu');
function ExporttoExcel_admin_menu() {
	add_options_page('ExporttoExcel', 'ExporttoExcel', 'administrator',
		'ExporttoExcel', 'ExporttoExcel_html_page');
}
function ExporttoExcel_html_page(){
	global $wpdb;
	?>
	<link type="text/css" rel='stylesheet' href="<?php	echo plugins_url('/ExporttoExcel.css', __FILE__ ); ?>">
	<div class="wrappered">
		<div class="wrap">
			<div  id="tabs">
				<ul>
					<li><a href="javascript:void(0)" data="onehurdred">Export</a></li>
					<div style="clear:both;"></div>
				</ul>
			</div>
			<div class="blocksection onehurdred">
				<h3>Registration Details</h3>
				<p>Please select option for geting details</p>
				<br/>
				
				<div>
					<form action="" method="POST">
						<select name="table_name">
							<option value=0>Select Form</option>
							<?php
							$sql = "SHOW TABLES";
							$table_list  = $wpdb->get_results($sql,ARRAY_N);

							foreach($table_list as $table_name){
								foreach ($table_name as $singlevalue){



									?>

									<option value="<?php echo $singlevalue; ?>"><?php echo $singlevalue; ?></option>	
									<?php	

								}
							}


							?>
						</select>
						<input type="submit" class="button button-primary"  value="Click for details"/>
					</form>
				</div>
			</div>
			<div style="overflow-x:scroll;">
				<table class="wp-list-table widefat plugins" id="example">
					<?php
					if(isset($_POST["table_name"])){
						$tablename = $_POST["table_name"];
						$i=1;
						echo "<tr style=''>";
						
						//echo $wpdb->dbname; 
						
						$t = mysql_connect($wpdb->dbhost,$wpdb->dbuser,$wpdb->dbpassword);
						mysql_select_db($wpdb->dbname,$t ) or die("Couldn't select database.");
						$result = mysql_query("SHOW COLUMNS FROM $tablename");

						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						if (mysql_num_rows($result) > 0){
							while ($row = mysql_fetch_assoc($result)) {
								echo "<th style=''>".$row["Field"]."</th>";
								$i++;
							}
						}
						echo "</tr>";
						$query = mysql_query("SELECT * FROM $tablename");
		//echo $total_rows = mysql_num_rows($query);
						while($row = mysql_fetch_array($query)){
					//var_dump($row $Offer as $key => $value);
							echo "<tr style=''>";
							for($d = 0;$d<$i-1; $d++){
								echo "<td style='' >".$row[$d]."</td>";
							}
							echo "</tr>";
						}

						?>
						<form action="<?php	echo plugins_url(); ?>/Table2Excel/form_download.php" method="POST">
							<input type="hidden" name="selected_table" value="<?php echo $tablename; ?>"/>
							<input type="hidden" name="selected_dbhost" value="<?php echo $wpdb->dbhost; ?>"/>
							<input type="hidden" name="selected_dbname" value="<?php echo $wpdb->dbname; ?>"/>
							<input type="hidden" name="selected_dbpassword" value="<?php echo $wpdb->dbpassword;  ?>"/>
							<input type="hidden" name="selected_dbuserer" value="<?php echo $wpdb->dbuser; ?>"/>
							<input class="button button-primary custom_ci" type="submit" name="table_display" value="Export"/>
						</form>
						<?php } 

						//echo "hiii".$wpdb->dbname;
						?>

					</table>
					
				</div>	
			</div>
		</div>

		<?php wp_enqueue_script('jquery'); ?>


		<script type="text/javascript" src="<?php	echo plugins_url('/ExporttoExcel.js', __FILE__ ); ?>"></script>
		<?php
	}
	ob_clean();
	?>
