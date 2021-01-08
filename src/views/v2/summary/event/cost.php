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
?>
<div class="tribe-events-c-small-cta tribe-common-b3 tribe-events-calendar-summary__event-cost">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( $event->title ); ?>"
		rel="bookmark"
		class=" tribe-common-b3--bold tribe-events-c-small-cta__text"
	><?php echo sprintf( __( 'Get %1$s', 'the-events-calendar' ), tribe_get_ticket_label_plural() ); ?></a>
	</span>
	<span class="tribe-events-c-small-cta__price">
		<?php echo esc_html( $event->cost ) ?>
	</span>
</div>
