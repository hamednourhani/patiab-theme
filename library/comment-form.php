<?php 

// Custom Comment Form
global $user_identity,$required_text;
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$required_text = __('<span class="required">Please fill the fields marked by <i class="icon-star"></i></span>','patiab');

$args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'title_reply'       => __( 'Leave a Reply','patiab' ),
  'title_reply_to'    => __( 'Leave a Reply to %s','patiab' ),
  'cancel_reply_link' => __( 'Cancel Reply<i class="fa fa-remove"></i>','patiab' ),
  'label_submit'      => __( 'Post Comment','patiab' ),

  'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . __( '<i class="icon-doc-text"></i> Comment', 'patiab' ) .
    '</label></p><p><textarea id="comment" name="comment" placeholder="'.__('your message','patiab').'" cols="70" rows="8" aria-required="true">' .
    '</textarea></p>',

  'must_log_in' => '<p class="must-log-in">' .
    sprintf(
      __( 'You must be <a href="%s">logged in</a> to post a comment.','patiab' ),
      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
    ) . '</p>',

  'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','patiab' ),
      admin_url( 'profile.php' ),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
    ) . '</p>',

  'comment_notes_before' => '<p class="comment-notes">' .
    __( 'Your email address will not be published.','patiab' ).'</p><p>' . ( $req ? $required_text : '' ) .
    '</p>',

  'comment_notes_after' => '<p class="form-allowed-tags">' .
    sprintf(
      __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s','patiab' ),
      ' <code>' . allowed_tags() . '</code>'
    ) . '</p>',

  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<p class="comment-form-author">' .
      '<label for="author">' . __( '<i class="fa fa-user"></i> Name', 'patiab' ) . '</label></p><p><input id="author" name="author" type="text" placeholder="'.__('yourname','patiab').'" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' />' .
      ( $req ? '<span class="required"></span>' : '' ) .
      '</p>',

    'email' =>
      '<p class="comment-form-email"><label for="email">' . __( '<i class="fa fa-envelope"></i> Email', 'patiab' ) . '</label></p><p><input id="email" name="email" type="text" placeholder="'.__('yourname@example.com','patiab').'" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' />' .
      ( $req ? '<span class="required"></span>' : '' ) .
      '</p>',

    'url' =>
      '<p class="comment-form-url"><label for="url">' .
      __( '<i class="fa fa-link"></i> Website', 'patiab' ) . '</label></p><p>' .
      '<input id="url" name="url" type="text" placeholder="'.__('www.example.com','patiab').'" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></p>'
    )
  ),
);

comment_form( $args); 

?>