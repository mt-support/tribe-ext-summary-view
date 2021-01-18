<?php
/**
 * View: List Single Event Cost
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/cost.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 1.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 */

if ( empty( $event->cost ) ) {
	return;
}

// ET isn't loaded correctly, and we need this function.
if ( ! function_exists( 'tribe_get_ticket_label_plural' )) {
	return;
}

$is_sold_out = $event->tickets->sold_out();

?>
<div class="tribe-events-c-small-cta tribe-common-b3 tribe-events-calendar-summary__event-cost">
	<?php if ( $is_sold_out ) : ?>
		<span class="tribe-common-b3--bold tribe-events-c-small-cta__text">
			<?php echo esc_html( __( 'Sold out', 'the-events-calendar' ) ); ?>
		</span>
	<?php else: ?>
		<a
			href="<?php echo esc_url( $event->permalink . '#tribe-tickets__tickets-form' ); ?>"
			title="<?php echo esc_attr( $event->title ); ?>"
			rel="bookmark"
			class=" tribe-common-b3--bold tribe-events-c-small-cta__text"
		><?php echo esc_html( sprintf( __( 'Get %1$s', 'the-events-calendar' ), tribe_get_ticket_label_plural() ) ); ?></a>
		</a>
		<span class="tribe-events-c-small-cta__price">
			<?php echo esc_html( $event->cost ) ?>
		</span>
	<?php endif; ?>
</div>
