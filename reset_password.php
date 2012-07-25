<?php
/*
    Plugin Name: Reset User Password
    Description: Plugin for resetting user passwords. Email notifications are automatically sent to the user.
    Author: Gareth Arnott
    Version: 1.0
    Author URI: http://www.garetharnott.com
*/

add_action( 'admin_menu', 'nt_reset_password_menu' );

//Sets page up and adds menu item. Second argument sets the name of page in menu. null=no menu item.
function nt_reset_password_menu() {
	add_users_page( 'Reset User Password', null, 'manage_options', 'reset-user-password', 'nt_reset_password_options' );
}

//Pull in reset_password_admin.php to use as content for the reset password page.
function nt_reset_password_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include("reset_password_admin.php");
}

//Add link to user profile page for Reset Password
add_action( 'show_user_profile', 'nt_reset_pass_profile_fields' );
add_action( 'edit_user_profile', 'nt_reset_pass_profile_fields' );

function nt_reset_pass_profile_fields( $user ) {
$userinfo = $_GET['user_id'];
?>

<h3><?php _e("Reset User Password", "blank"); ?></h3>
<table>
	<tr>
		<th></th>
		<td>
			<a href="users.php?page=reset-user-password&change_user_id=<?php echo $userinfo; ?>"><?php _e("Reset Password");?></a>
			<span class="description"><?php _e("Click to reset this user's password"); ?></span>
		</td>
	</tr>
</table>
<?php } ?>