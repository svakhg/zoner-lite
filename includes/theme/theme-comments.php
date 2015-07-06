<?php 
	if (!function_exists("zoner_custom_comments")) {
		function zoner_custom_comments( $comment, $args, $depth ) {
			global $zoner_config, $prefix;
			$GLOBALS['comment'] = $comment; 
			?>
			<li <?php comment_class(); ?>>
				<?php 
					$avatar_img = $avatar = '';
					if (!zoner_validate_gravatar($comment->comment_author_email)) {
						 $avatar_img = '<img width="100%" class="img-responsive" data-src="holder.js/86x86?text='. __('Author', 'zoner') .'" alt="" />';
					} else {
						 $all_meta_for_user = get_user_meta( $comment->user_id );
						 if (!empty($all_meta_for_user[$prefix.'avatar']))
							$avatar = $all_meta_for_user[$prefix.'avatar'];
						 if (!empty($all_meta_for_user[$prefix.'avatar_id']))
							$avatar_id = $all_meta_for_user[$prefix.'avatar_id'];
							
						if (!empty($avatar))  {
							$avatar_img = '<img width="100%" class="img-responsive" src="'.$avatar.'" alt="" />';
						} else {
							$avatar_img = zoner_commenter_avatar($args);
						}
					}
				?>
				
				<figure>
					<div class="image">
						<?php echo $avatar_img;  ?>
					</div>
				</figure>
				<div class="comment-wrapper">
					<div class="name"><?php comment_author_link(); ?></div>
					<span class="date"><span class="fa fa-calendar"></span><?php echo get_comment_date(get_option( 'date_format' )) ?> <?php _e('at', 'zoner'); ?> <?php echo get_comment_time(get_option( 'time_format' )); ?></span>
					
					<?php comment_text() ?>
					<?php $myclass = 'reply';
						  echo preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $myclass, get_comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span class="fa fa-reply"></span>' . __('Reply', 'zoner')))), 1 );
					?>	  
					<?php edit_comment_link(__('Edit this comment', 'zoner'), '', ''); ?>
					<?php if ($comment->comment_approved == '0') { ?>
						<p class='unapproved'><?php _e('Your comment is awaiting moderation.', 'zoner'); ?></p>
					<?php } ?>
					<hr>
				</div><!-- /.comment-content -->
			<?php
		}
	}

	if ( ! function_exists( 'zoner_commenter_avatar' ) ) {
		function zoner_commenter_avatar( $args ) {
			global $comment;
			
			$avatar = get_avatar( $comment,  86 );
			return $avatar;
		}
	}
	
	if ( ! function_exists( 'zoner_validate_gravatar' ) ) {
		function zoner_validate_gravatar($email) {
			$hash = md5(strtolower(trim($email)));
			$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
			$headers = @get_headers(esc_url($uri));
			if (!preg_match("|200|", $headers[0])) {
				$has_valid_avatar = FALSE;
			} else {
				$has_valid_avatar = TRUE;
			}
			return $has_valid_avatar;
		}
	}