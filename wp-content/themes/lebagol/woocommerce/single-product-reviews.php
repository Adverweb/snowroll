<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}


$counts = lebagol_get_review_counting();
$average      = $product->get_average_rating();
$count = $product->get_review_count();
?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<?php if ( have_comments() ) : ?>
            <div class="reviews-summary">
                <div class="review-summary-total">
                    <div class="review-summary-info">
                        <div class="review-summary-result">
                            <strong><?php echo floatval($average); ?></strong>
                        </div>
                        <div class="review-summary-rating">
                            <?php echo wc_get_rating_html($average);?>
                            <?php echo sprintf(esc_html( _n( '%1$s verified rating', '%1$s verified ratings', $count, 'lebagol' ) ),esc_html( $count )); ?>
                        </div>
                    </div>
                     <div class="review-summary-btn">
                        <a href="#respond" class="button">  <?php echo esc_html__( 'Write a review', 'lebagol' ); ?><i class="lebagol-icon-arrow-right"></i></a>
                     </div>
                </div>
                <div class="review-summary-detal">
                    <?php foreach( $counts as $key => $value ):  $pc = ($count == 0 ? 0: ( ($value/$count)*100  ) ); ?>
                        <div class="review-summery-item">
                            <div class="progress-title"><span class="review-star-number"><?php echo esc_html($key);?></span><i class="lebagol-icon-star"></i><span class="review-number-count">(<?php echo esc_html($value);?>)</span></div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo esc_attr($pc);?>%;">
                                    <?php echo round($pc,2);?>%
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
							'next_text' => is_rtl() ? '&larr;' : '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'lebagol' ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
				$commenter    = wp_get_current_commenter();
				$comment_form = array(
					/* translators: %s is product title */
					'title_reply'         => have_comments() ? esc_html__( 'Write a review', 'lebagol' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'lebagol' ), get_the_title() ),
					/* translators: %s is product title */
					'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'lebagol' ),
					'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
					'title_reply_after'   => '</span>',
					'comment_notes_after' => '',
					'label_submit'        => esc_html__( 'Submit', 'lebagol' ),
					'logged_in_as'        => '',
					'comment_field'       => '',
				);

				$name_email_required = (bool) get_option( 'require_name_email', 1 );
				$fields              = array(
					'author' => array(
						'label'    => __( 'Name', 'lebagol' ),
						'type'     => 'text',
						'value'    => $commenter['comment_author'],
						'required' => $name_email_required,
                        'placeholder'=>__('Name *', 'lebagol'),
					),
					'email'  => array(
						'label'    => __( 'Email', 'lebagol' ),
						'type'     => 'email',
						'value'    => $commenter['comment_author_email'],
						'required' => $name_email_required,
                        'placeholder'=>__('Email *', 'lebagol'),
					),
				);

				$comment_form['fields'] = array();

				foreach ( $fields as $key => $field ) {
					$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';

					$field_html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

					$comment_form['fields'][ $key ] = $field_html;
				}

				$account_page_url = wc_get_page_permalink( 'myaccount' );
				if ( $account_page_url ) {
					/* translators: %s opening and closing link tags respectively */
					$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'lebagol' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
				}

				if ( wc_review_ratings_enabled() ) {
					$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'lebagol' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'lebagol' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'lebagol' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'lebagol' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'lebagol' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'lebagol' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'lebagol' ) . '</option>
					</select></div>';
				}

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" placeholder="' . esc_attr( __('Your Review *', 'lebagol') ) . '" name="comment" cols="45" rows="8" required></textarea></p>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'lebagol' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
