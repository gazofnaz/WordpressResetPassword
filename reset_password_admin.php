<?php

global $wpdb;

function nt_random_password($length = 5) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function nt_reset_password_email($thepass){
	//the variables
	$userdata = get_userdata($_GET['change_user_id']);
	$useremail = $userdata->user_email;
	echo $useremail;
	$user_first_name = get_user_meta($_GET['change_user_id'], 'first_name', true);
	$user_last_name = get_user_meta($_GET['change_user_id'], 'last_name', true);
	$blog_title = get_bloginfo('name');
	$blog_url = get_bloginfo('url');
	$blog_tagline = get_bloginfo('description');

	//the email
	$headers = "From: " . $blog_title. " <noreply@informa.com>\r\n";
	$subject = "Your Password has been Reset";
	$message = "Dear ".$user_first_name." ".$user_last_name.
	",\r\n\r\nYour Password for ".$blog_title." ".$blog_tagline." has been reset to: ".$thepass.
	"\r\n\r\nYou can visit the ".$blog_title." ".$blog_tagline." website here - ".$blog_url.
	"\r\n\r\n* Please note, this password will be effective for all Informa Event Sites.
	\r\n\r\nKind Regards
	\r\n\r\nThe ".$blog_title." Team";

	wp_mail($useremail, $subject, $message, $headers, $attachments );
}



		//3 flags to show/hide the user errors on the page
		$showDivFlagComplete=false;

		$showDivFlagMismatch=false;

		$showDivFlagBlank=false;

		if($_POST['password_change'])
		{

		//needed for wp_update_user()
		require_once( ABSPATH . WPINC . '/registration.php');


			if($_POST['password_change'])
			{

				//receive new password 1 from form
				$newpass1 = $_POST['newpass1'];

				//receive new password 2 from form
				$newpass2 = $_POST['newpass2'];


				//assign id to wp_user_data array
				$new_user_data["ID"] = $_GET['change_user_id'];

				$strTemp1 = trim($newpass1);

				$strTemp2 = trim($newpass2);

				if($_POST['randompass']!='Yes')
				{
					if($strTemp1 !== '' || $strTemp2 !== '')
					{
						if($newpass1 == $newpass2)
						{//SUCCESS
							//set user_data array with new password
							$new_user_data["user_pass"] = $newpass1;

							//update database
							wp_update_user( $new_user_data );

							//show "Password Changed!" message
							$showDivFlagComplete=true;

							$thepass = $newpass1;

							nt_reset_password_email($thepass);

						}

						else
						{
							//show "Passwords Do Not Match" message
							$showDivFlagMismatch=true;

						}
					}
					else
					{
						//show "Your password is blank" message
						$showDivFlagBlank=true;

					}
				}
				else
				//What to do if random box is checked.
				{
							//get random password from function
							$random_pass = nt_random_password();

							//assign password to wp_user_data array
							$new_user_data["user_pass"] = $random_pass;

							//update database
							wp_update_user( $new_user_data );

							//show "Password Changed!" message
							$showDivFlagComplete=true;

							$thepass = $random_pass;

							//call email function
							nt_reset_password_email($thepass);

				}
			}
		}

?>
<div class="wrap">
	<div id="icon-users" class="icon32"><br></div>
	<h2>Reset Password</h2>
	<p>This form will reset the user's password. An email will automatically be sent to the user with a link to this site, as well as their username and new password.</p>
			<form name='frm' id="frm" method='post' enctype="multipart/form-data" >

				<table>
					<tr>
						<td>
							<h3><?php _e("User");?></h3>
						</td>
					</tr>
					<tr>
						<th></th>
						<td><?php _e("Email");?></td>
						<td><strong><?php echo get_userdata($_GET['change_user_id'])->user_email; ?></strong></td>
					</tr>
					<tr>
						<td>
							<h3><?php _e("Either");?></h3>
						</td>
					</tr>
					<tr>
						<th></th>
						<td><label for="newpass1"><?php _e("Enter New Password:");?></label></td>
						<td><input id="newpass1" name="newpass1"  type="password" maxlength="255" value="" class="edit-input"/> </td>
					</tr>

					<tr>
						<td></td>
						<td><label for="newpass2"><?php _e("Re-type:");?></label></td>
						<td><input id="newpass2" name="newpass2" type="password" maxlength="255" value="" class="edit-input"/></td>
					</tr>

					<tr>
						<td>
							<h3><?php _e("Or");?></h3>
						</td>
					</tr>

					<tr>
						<th></th>
						<td><label for="newpass2"><?php _e("Assign Random Password:");?></label></td>
						<td><input id="randompass" name="randompass" type="checkbox" value="Yes" class="edit-input"/></td>
					</tr>
					<tr>
						<th></th>
						<td></td>
						<td height="50">
							<input type="hidden" name="password_change" value="submit" />
							<input type="submit" name="submit" value="Submit" style="width:100px; height:30px"/>
						</td>
					</tr>

					<tr>
						<th></th>
						<td></td>
						<td>
						<!--ERROR MESSAGES-->
							<div <?php if ($showDivFlagComplete===false){?>style="display:none"<?php } ?>>
								<strong style="color:green;"><?php _e("Password Changed, Email Sent!");?></strong>
								<p><a href="<?php bloginfo('url'); ?>/wp-admin/users.php"><?php _e("Search for more users");?></a></p>
							</div>

							<div <?php if ($showDivFlagMismatch===false){?>style="display:none"<?php } ?>>
								<strong style="color:red;"><?php _e("* Your new passwords do not match, please retype");?></strong>
							</div>

							<div <?php if ($showDivFlagBlank===false){?>style="display:none"<?php } ?>>
								<strong style="color:red;"><?php _e("* Please enter a password or tick the box");?></strong>
							</div>
						<!--END ERROR MESSAGES-->
						</td>
					</tr>

				</table>

			</form>
</div><!--wrap-->