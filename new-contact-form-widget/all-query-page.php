<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// action request handler
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if(!empty($action)) {
	if (current_user_can('manage_options')) {
		//view contact query
		if($action == "view-contact-query") {
			$id =(int)$_POST['id'];
			// Verify nonce
			if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'view_contact_query_' . $id)) {
				wp_die( esc_html__( 'Nonce verification failed.', 'new-contact-form-widget' ) );
			}
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$user_searh_query_result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `id` = %d", $id ));
			if($user_searh_query_result){
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
				exit;
			}
		}

		//delete query
		if($action == "delete-contact-query") {
			$id = (int)$_POST['id'];
			// Verify nonce
			if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'delete_contact_query_' . $id)) {
				wp_die('Nonce verification failed.');
			}
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			if($wpdb->delete($table_name, array('id' => $id), array('%d'))) {
				echo "success-delete.";
			}
			exit;
		}

		// Multiple delete user queries
		if ($action == "delete-all-queries") {
			// Verify nonce
			if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bulk_delete_queries')) {
				wp_die( esc_html__( 'Nonce verification failed.', 'new-contact-form-widget' ) );
			}
			global $wpdb;
			$table_name = $wpdb->prefix . 'awp_contact_form';
			$ids_string = isset($_POST['id']) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$ids = array_filter( array_map( 'absint', explode( ",", $ids_string ) ) );

			if ( ! empty( $ids ) ) {
				$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
				$deleted = $wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE id IN ($placeholders)", $ids ) );
				if ( false !== $deleted ) {
					echo "success-bulk-delete";
				} else {
					echo "error-bulk-delete";
				}
			} else {
				echo "error-bulk-delete";
			}
			exit;
		}
	}
}

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
	<a class="btn btn-primary" style="float: right; margin-top: 20px; margin-right: 20px;" href="<?php echo esc_url( admin_url( 'admin.php?page=cfw-all-queries&action=download-user-list&_wpnonce=' . wp_create_nonce( 'download_user_list_action' ) ) ); ?>"><i style="margin-right: 10px;" class="fa fa-download"></i><?php esc_html_e('Download Query List', 'new-contact-form-widget'); ?></a>
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
			<th class="text-center"><input type="checkbox" id="selectAll"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			// Fetch total user queries count efficiently
			global $wpdb;
			$contact_form_table_name = $wpdb->prefix . 'awp_contact_form';
			$total_records = (int) $wpdb->get_var( "SELECT COUNT(*) FROM `$contact_form_table_name`" );
			
			// Pagination limit start
			$per_page = max( 1, (int) $show_record_per_page );
			$total_pages = (int) ceil( $total_records / $per_page );
			$show_page = 1;

			if ( isset( $_GET['page_no'] ) ) {
				$show_page = absint( $_GET['page_no'] );
				if ( $show_page < 1 ) {
					$show_page = 1;
				} elseif ( $total_pages > 0 && $show_page > $total_pages ) {
					$show_page = $total_pages;
				}
			}
			$start = ( $show_page - 1 ) * $per_page;
			$tpages = $total_pages;

			// Fetch only current page records using prepared statement
			$new_all_contact_queries_result = $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM `$contact_form_table_name` ORDER BY date_time DESC LIMIT %d OFFSET %d", $per_page, $start ),
				OBJECT
			);

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
							<?php $view_nonce = wp_create_nonce('view_contact_query_' . $id); ?>
							<button class="btn btn-info" onclick="ManageContactQueries('view-contact-query','<?php echo esc_attr($id); ?>', '<?php echo esc_attr($view_nonce); ?>');"><i style="margin-right: 10px;" class="fa fa-eye"></i><?php esc_html_e('View Query', 'new-contact-form-widget'); ?></button>
						</td>
						<td class="text-center">
							<?php $delete_nonce = wp_create_nonce('delete_contact_query_' . $id); ?>
							<button class="btn btn-danger text-center" href="?page=cfw-all-queries&action=delete-contact-query&id=<?php echo esc_attr($id); ?>" onclick="ManageContactQueries('delete-contact-query', '<?php echo esc_js($id); ?>', '<?php echo esc_attr($delete_nonce); ?>');">
								<i class="fa fa-trash-o"></i>
							</button>
						</td>
						<td class="text-center"><input type="checkbox" class="query-checkbox" value="<?php echo esc_attr($id); ?>"></td>
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
			<td class="text-center"><button class="btn btn-danger" onclick="return ManageContactQueries('delete-all-queries','-1', '<?php echo esc_js(wp_create_nonce('bulk_delete_queries')); ?>');"><i class="fa fa-trash-o"></i></button></td>					
		</tr>
		<tr class="info">			
			<?php
			/*--------------------------------------------------------------------------------------------
			|    @desc:         pagination 
			---------------------------------------------------------------------------------------------*/
			if ( ! function_exists( 'cfw_paginate' ) ) {
				function cfw_paginate($reload, $page, $tpages) {
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
						$out.= "<li><a  href=\"" . $reload . "&amp;page_no=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
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
			}
			?>			
			<td colspan="7" class="text-center">
				<?php
				$reload = admin_url( 'admin.php?page=cfw-all-queries&tpages=' . $tpages );
				echo '<ul class="pagination">';
				if ($total_pages > 1) {
					echo cfw_paginate($reload, $show_page, $total_pages);
				}
				echo "</ul>";
				?>
			</td>
		</tr>
	</tbody>
</table>

<!-- View Query Modal Form -->
<style>
#user-modal-form {
	z-index: 999999 !important;
	background: rgba(0,0,0,0.5);
}
#user-modal-form .modal-dialog {
	margin-top: 100px !important;
}
</style>
<div id="user-modal-form" class="modal bs-example-modal-lg" tabindex="-1" role="dialog" style="display: none;">
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
function ManageContactQueries(action, id, nonce) {
	if (action == "view-contact-query") {
		// show modal form
		jQuery("#user-modal-form").show();
		jQuery("#loading-msg").show();
		jQuery('#query-details').html('<div id="loading-msg" class="text-center"><h3>Loading...</h3><i class="fa fa-cog fa-spin fa-3x"></i></div>');

		var PostData = 'action=' + action + '&id=' + id + '&_wpnonce=' + nonce;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : "?page=cfw-all-queries",
			cache: false,
			data : PostData,
			success: function(data) {
				jQuery("#loading-msg").hide();
				// Robust extraction that handles both full page and partial responses
				var content = jQuery('<div>').html(data).find('#view-query-data');
				if (content.length > 0) {
					jQuery('#query-details').html(content);
				} else {
					jQuery('#query-details').html(data);
				}
			}
		});
	}

	if (action == "delete-contact-query") {
		if (confirm('Are you sure want to delete this contact query?')) {
			jQuery.post(location.href, { action: action, id: id, _wpnonce: nonce }, function(response) {
				if (response.indexOf("success-delete") >= 0) {
					jQuery("#cq-" + id).fadeOut(1500);
				}
			});
		}
	}

	if (action == "delete-all-queries") {
		var ids = [];
		jQuery('.query-checkbox:checked').each(function() {
			ids.push(jQuery(this).val());
		});

		if (ids.length === 0) {
			alert('Please select at least one query to delete.');
			return false;
		}

		if (confirm('Are you sure you want to delete all selected contact queries?')) {
			var ids_string = ids.join(',');
			jQuery.post(location.href, { action: action, id: ids_string, _wpnonce: nonce }, function(response) {
				if (response.indexOf("success-bulk-delete") >= 0) {
					location.reload();
				} else {
					alert('Error: Unable to delete selected contact queries.');
				}
			});
		}
	}
}

jQuery(document).ready(function($) {
	$("#selectAll").change(function () {
		$(".query-checkbox").prop('checked', $(this).prop("checked"));
	});

	// Close modal manual fallback
	$(document).on('click', '[data-dismiss="modal"]', function() {
		$("#user-modal-form").hide();
	});
});
</script>

<?php
// pagination limit end
?>