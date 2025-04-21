<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// load css and js files
wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/bootstrap.css' );
wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
wp_enqueue_script( 'cfw-boostrap-js', plugin_dir_url( __FILE__ ).'js/bootstrap.js', array('jquery'), '3.3.6', true );

// load pagination setting
$all_setttings = get_option('contact_form_settings');

if(isset($all_setttings['show_query'])) {
	$show_record_per_page = $all_setttings['show_query'];
} else {
	$show_record_per_page = 10;
}
?>
<div>
	<h1 style="float: left; width:45%; "><?php esc_html_e('All Users Queries', 'new-contact-form-widget'); ?></h1>
	<a class="btn btn-primary" style="float: right; margin-top: 20px; margin-right: 20px;" onclick="return DownloadList(<?php echo wp_create_nonce( "download_user_list_action" ); ?>);"><i style="margin-right: 10px;" class="fa fa-download"></i><?php esc_html_e('Download All User List', 'new-contact-form-widget'); ?></a>
</div>
<table class="table  table-bordered table-hover" style="background-color: #FFFFFF;">
	<thead>
		<tr class="info">	
			<th><?php esc_html_e('# ID', 'new-contact-form-widget'); ?></th>
			<th><?php esc_html_e('User Name', 'new-contact-form-widget'); ?></th>
			<th><?php esc_html_e('User Email', 'new-contact-form-widget'); ?></th>
			<th><?php esc_html_e('Date Time', 'new-contact-form-widget'); ?></th>
			<th class="text-center"><?php esc_html_e('View Query', 'new-contact-form-widget'); ?></th>
			<th class="text-center"><?php esc_html_e('Delete', 'new-contact-form-widget'); ?></th>
			<th class="text-center"><input type="checkbox" name="checkAll" id="checkAll"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			//fetch all user queries
			global $wpdb;
			$contact_form_table_name = $wpdb->prefix . 'awp_contact_form';
			$all_contact_queries_result = $wpdb->get_results("SELECT * FROM `$contact_form_table_name`", OBJECT );
			
			// pagination limit start
			$per_page = $show_record_per_page;															// number of results to show per page
			$total_results = $all_contact_queries_result;							// all results
			$total_pages = ceil(count($all_contact_queries_result) / $per_page);	// total pages we going to have
			$show_page = 1;															// which page will be display

			//-------------if page is set check------------------//
			if (isset($_GET['page_no'])) {
				$show_page =sanitize_text_field($_GET['page_no']);             //it will tells the current page
				if ($show_page > 0 && $show_page <= $total_pages) {
					$start = ($show_page - 1) * $per_page;
					$end = $start + $per_page;
				} else {
					// error - show first set of results
					$start = 0;              
					$end = $per_page;
				}
			} else {
				// if page isn't set, show first set of results
				$start = 0;
				$end = $per_page;
			}
			// display pagination
			if(isset($_GET['page_no'])) {
				$page = intval($_GET['page_no']);
			} else {
				$page = 5;
			}
			$tpages = $total_pages;
			if ($page <= 0) $page = 1;
			// pagination limit end
			$new_all_contact_queries_result = $wpdb->get_results("SELECT * FROM `$contact_form_table_name` ORDER BY date_time DESC LIMIT $per_page OFFSET $start", OBJECT );

			//print_r($new_all_contact_queries_result);
			if(count($new_all_contact_queries_result)){
				$no = 1;
				if($show_page > 1) { $no = $start + 1; }
				foreach($new_all_contact_queries_result as $single_row ){
					$id = $single_row->id;
					$name = $single_row->name;
					$email = $single_row->email;
					$date_time = $single_row->date_time;
					?>
					<tr id="cq-<?php echo esc_attr($id); ?>">
						<td><?php echo esc_attr($no); ?></td>
						<td><?php echo esc_html($name); ?></td>
						<td><?php echo esc_html($email); ?></td>
						<td><?php echo esc_attr($date_time); ?></td>
						<td class="text-center">
							<button class="btn btn-info" data-toggle="modal" data-target="#user-modal-form" onclick="ManageContactQueries('view-contact-query','<?php echo esc_attr($id); ?>');"><i style="margin-right: 10px;" class="fa fa-eye"></i><?php esc_html_e('View Query', 'new-contact-form-widget'); ?></button>
						</td>
						<td class="text-center">
							<?php $delete_nonce = wp_create_nonce('delete_contact_query_' . $id); ?>
							<button class="btn btn-danger text-center" href="?page=cfw-all-queries&action=delete-contact-query&id=<?php echo esc_attr($id); ?>" onclick="ManageContactQueries('delete-contact-query', '<?php echo esc_js($id); ?>', '<?php echo esc_attr($delete_nonce); ?>');">
								<i class="fa fa-trash-o"></i>
							</button>
						</td>
						<td class="text-center"><input type="checkbox" name="checkAll" id="checkAll" value="<?php echo esc_attr($id); ?>"></td>
					</tr>
					<?php
					$no++;
				}
			}
		?>
		<tr class="">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-center"><button class="btn btn-danger" onclick="return ManageContactQueries('delete-all-queries','-1');"><i class="fa fa-trash-o"></i></button></td>					
		</tr>
		<tr class="info">			
			<?php
			/*--------------------------------------------------------------------------------------------
			|    @desc:         pagination 
			---------------------------------------------------------------------------------------------*/
			function paginate($reload, $page, $tpages) {
				$adjacents = 1;
				$prevlabel = "&lsaquo; Prev";
				$nextlabel = "Next &rsaquo;";
				$out = "";
				// previous
				if ($page == 1) {
					$out.= "";
				} elseif ($page == 2) {
					$out.= "<li><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
				} else {
					// previous
					$out.= "<li><a  href=\"" . $reload . "&amp;page_no=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";//beech ka diffrance change krega 
				}
			  
				$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
				$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
				for ($i = $pmin; $i <= $pmax; $i++) {
					if ($i == $page) {
						$out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
					} elseif ($i == 1) {
						$out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
					} else {
						$out.= "<li><a  href=\"" . $reload . "&amp;page_no=" . $i . "\">" . $i . "</a>\n</li>";
					}
				}
				
				if ($page < ($tpages - $adjacents)) {
					$out.= "<li><a href=\"" . $reload . "&amp;page_no=" . $tpages . "\">" . $tpages . "</a></li>\n";
				}
				// next
				if ($page < $tpages) {
					$out.= "<li><a href=\"" . $reload . "&amp;page_no=" . ($page + 1) . "\">" . $nextlabel . "</a></li>";
				} else {
					$out.= "";
				}
				$out.= "";
				return $out;
			}
			?>			
			<td colspan="7" class="text-center">
				<?php
				$reload = $_SERVER['PHP_SELF'] . "?page=cfw-all-queries&tpages=" . $tpages;
				echo '<ul class="pagination">';
				if ($total_pages > 1) {
					echo paginate($reload, $show_page, $total_pages);
				}
				echo "</ul>";
				?>
			</td>
		</tr>
	</tbody>
</table>

<!-- View Query Modal Form -->
<div id="user-modal-form" name="user-modal-form" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title"><?php esc_html_e('View Contact Query Details', 'new-contact-form-widget'); ?></h3>
			</div>
			<div id="query-details" class="modal-body">
				<div id="loading-msg" class="text-center">
					<h3><?php esc_html_e('Loading Contact Query Details...', 'new-contact-form-widget'); ?></h3>
					<i class="fa fa-cog fa-spin fa-3x "></i>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal"><?php esc_html_e('Close', 'new-contact-form-widget'); ?></button>
		</div>
		</div>
	</div>
</div>

<script>
<?php  

$rand = wp_rand();
$upload_dir = wp_upload_dir();

?>
function DownloadList(nonce){
	//Ajax download user list code
	var DownloadUsersList = new XMLHttpRequest();
	//check object request & response
	DownloadUsersList.onreadystatechange = function() {
		if (DownloadUsersList.readyState == 4 && DownloadUsersList.status == 200) {
			 if((DownloadUsersList.responseText.indexOf("file-created") >= 0)) {
                var file_path = DownloadUsersList.responseText;
                if(file_path !== "File not created.") {
                   var create_file_url = '<?php echo $upload_dir["baseurl"] . "/all-users-list-" . $rand . ".csv"; ?>';
                    window.open(create_file_url, '_blank'); // This will initiate file download

                    // Now you need to send another request to delete the file
                    var DeleteFileRequest = new XMLHttpRequest();
                    DeleteFileRequest.open("POST", location.href, true);
                    DeleteFileRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    DeleteFileRequest.send('action=delete-user-list&file_path=' + create_file_url);
                } else {
                    alert("File creation failed.");
                }
            }
		}

		if(DownloadUsersList.status == 404) {
			alert('File not found & object not responding.');
			return false;
		}
	};	

	var create_file_url = '<?php echo $upload_dir["baseurl"] . "/all-users-list-" . $rand . ".csv"; ?>';	
	//data post by object
	DownloadUsersList.open("POST", location.href, true);
	DownloadUsersList.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	DownloadUsersList.send('action=download-user-list&file_path=' + create_file_url + '&_wpnonce=' + nonce );
}

function ManageContactQueries(action, id, nonce) {
	//delete query
    if(action == "delete-contact-query") {
        if(confirm('Are you sure want to delete this contact query?')) {
            //ajax code
            var MangeCQ = new XMLHttpRequest();
            //check object request & response
            MangeCQ.onreadystatechange = function() {
                if (MangeCQ.readyState == 4 && MangeCQ.status == 200) {
                    if((MangeCQ.responseText.indexOf("success-delete") >= 0)) {
                        jQuery( "#cq-" + id ).fadeOut( 1500, "linear" );
                    }
                }

                if(MangeCQ.status == 404) {
                    alert('File not found & object not responding.');
                    return false;
                }
            };      
            //data post by object, send the nonce with the action and ID
            MangeCQ.open("POST", location.href, true);
            MangeCQ.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            MangeCQ.send('action=' + action + '&id=' + id + '&_wpnonce=' + nonce);
        }
    }
	
	//delete all query
	if(action == "delete-all-queries") {
		if(confirm('Are you sure want to delete all selected Queries?')) {
			var deleteallids = [];
			jQuery('input:checkbox:checked').map(function() {
				if(jQuery.isNumeric(this.value)) {
					deleteallids.push(this.value);
				}
			});
			console.log(deleteallids);
			
			// check if any website selected or not
			if(deleteallids.length) {
			
				//create ajax object
				var ManageCQ = new XMLHttpRequest();

				//check object request & response
				ManageCQ .onreadystatechange = function() {
					if (ManageCQ .readyState == 4 && ManageCQ .status == 200) {
						console.log(ManageCQ .responseText);
						for (i = 0; i < deleteallids.length; i++) {
							jQuery( "#cq-" + deleteallids[i] ).fadeOut( 1500, "linear" );
						}
					}

					if(ManageCQ .status == 404) {
						alert('File not found & object not responding.');
						return false;
					}
				};		

				//data post by object
				ManageCQ .open("POST", location.href, true);
				ManageCQ .setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ManageCQ .send('action=' + action + '&id=' + deleteallids );
			} else {
				alert("No Queries is selected to delete.");
			}
		}
	}
	
	//show query
	if(action == "view-contact-query") {
		//show modal form
		jQuery("#user-modal-form").show();
		jQuery("#loading-msg").show();
					
		var PostData = 'action=' + action + '&id=' + id ;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : "?page=cfw-all-queries",
			cache: false,
			data : PostData,
			complete : function() {  },
			success: function(data) {
				jQuery("#loading-msg").hide();
				jQuery('#query-details').html(jQuery(data).find('div#view-query-data'));					
			}
		});
	}
}

// select all check boxes jQuery
jQuery(document).ready(function() {
	jQuery("#checkAll").change(function () {
		jQuery("input:checkbox").prop('checked', jQuery(this).prop("checked"));
	});
});
</script>
<?php
// post query request
if(isset($_POST['action'])) {
	if (current_user_can('manage_options')) {
		$action = $_POST['action'];
		
		//view contact query
		if($action == "view-contact-query") {
			$id =(int)$_POST['id'];
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$user_searh_query_result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `id` = %d", $id ));
			if($user_searh_query_result){
				$id = $user_searh_query_result->id;
				$name = $user_searh_query_result->name;
				$email = $user_searh_query_result->email;
				$date_time = $user_searh_query_result->date_time;
				$subject = $user_searh_query_result->subject;
				$message = $user_searh_query_result->message;
				?>
				<div id="view-query-data">
					<p><strong><?php esc_html_e('User Name:', 'new-contact-form-widget'); ?> </strong><?php echo esc_html($name); ?></p>
					<p><strong><?php esc_html_e('User Email:', 'new-contact-form-widget'); ?> </strong><?php echo esc_html($email); ?></p>
					<p><strong><?php esc_html_e('User Subject:', 'new-contact-form-widget'); ?> </strong><p><?php echo esc_html($subject); ?></p></p>
					<p><strong><?php esc_html_e('User Query:', 'new-contact-form-widget'); ?> </strong><p><?php echo esc_html($message); ?></p></p>
					<p><strong><?php esc_html_e('Date Time:', 'new-contact-form-widget'); ?> </strong><?php echo esc_attr($date_time); ?></p>
				</div>
				<?php
			}
		}

		//delete query
		if($action == "delete-contact-query") {
			$id = (int)$_POST['id'];

			// Verify nonce
			if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'delete_contact_query_' . $id)) {
				echo 'Nonce verification failed.';
				wp_die('Nonce verification failed.');
			}

			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$where = array('id' => $id);
			$where_format = array( '%d' );
			if($wpdb->delete($table_name, $where, $where_format )) {
				echo "success-delete.";
			} else {
				echo "failed-delete.";
			}
		}

		// Multiple delete user queries
		if ($action == "delete-all-queries") {
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$ids_string = isset($_POST['id']) ? $_POST['id'] : ''; // Get IDs string from POST data
			$ids = explode(",", $ids_string); // Explode IDs string into an array

			// Count the number of IDs
			$count = count($ids);
			$n = 0;

			if (is_array($ids)) {
				foreach ($ids as $id) {
					// Ensure the ID is an integer
					$id = (int)$id;

					// Perform deletion for each ID
					if ($id > 0 && $wpdb->query($wpdb->prepare("DELETE FROM `$table_name` WHERE `id` = %d", $id))) {
						$n++;
					}
				}

				// Check if all selected queries were deleted successfully
				if ($n == $count) {
					echo "<h1 class='text-center alert alert-success'>All selected contact queries deleted successfully.</h1>";
				} else {
					echo "<h1 class='text-center alert alert-danger'>Error: Unable to delete all selected contact queries.</h1>";
				}
			}
		}

		//download user list
		if($action == "download-user-list") {
			
			// Verify nonce
			if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'download_user_list_action')) {
				echo 'Nonce verification failed.';
				wp_die('Nonce verification failed.');
			}

			// Assuming $create_file_path comes from $_POST['file_path'], you need to validate and sanitize it.
			$create_file_path = $_POST['file_path'];

			// Convert URL to local path
			$upload_dir = wp_upload_dir(); // Get WordPress upload directory paths
			$base_dir = $upload_dir['basedir']; // Local path to the upload directory

			// Extract the filename from the URL if it's coming as a full URL, safer to specify just a file name in the POST request
			$filename = basename($create_file_path); // Get the base name of the file

			// Construct the local file path
			$local_file_path = $base_dir . '/' . $filename;

			// File create and open
			if ($users_list_file = fopen($local_file_path, "w")) {
				// fetch all users form database
				$first_line_to_write = "#, Name, Email \n";
				fwrite($users_list_file, $first_line_to_write);

				global $wpdb;
				$table_name = $wpdb->prefix . 'awp_contact_form';
				$user_search_query_result = $wpdb->get_results( "SELECT * FROM `$table_name`" );

				if(count($user_search_query_result)){
					$no = 1;
					foreach($user_search_query_result as $single_row ){
						$id = $single_row->id;
						$name = $single_row->name;
						$email = $single_row->email;
						$txt_to_write = "$no, $name, $email \n";
						fwrite($users_list_file, $txt_to_write);
						$no++;
					}
				}

				fclose($users_list_file);
				echo "file-created.";
			} else {
				echo "File not created.";
			}
		}

		if($action == "delete-user-list") {
			// Assuming $create_file_path comes from $_POST['file_path'], you need to validate and sanitize it.
			$create_file_path = $_POST['file_path'];

			// Convert URL to local path
			$upload_dir = wp_upload_dir(); // Get WordPress upload directory paths
			$base_dir = $upload_dir['basedir']; // Local path to the upload directory

			// Extract the filename from the URL if it's coming as a full URL, safer to specify just a file name in the POST request
			$filename = basename($create_file_path); // Get the base name of the file

			// Construct the local file path
			$local_file_path = $base_dir . '/' . $filename;

			if(file_exists($local_file_path)) {
				unlink($local_file_path);
				echo "File deleted.";
			} else {
				echo "File does not exist.";
			}
		} 
	}
}
?>