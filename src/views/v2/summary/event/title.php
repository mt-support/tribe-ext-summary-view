<?php
/**
 * View: Summary View - Single Event Title
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/title.php
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
 */
?>
<h3 class="tribe-events-calendar-summary__event-title tribe-common-h8 tribe-common-h7--min-medium">
	<?php $this->template( 'summary/event/title/featured' ); ?>
	<?php $this->template( 'summary/event/title/virtual' ); ?>
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( $event->title ); ?>"
		rel="bookmark"
		class="tribe-events-calendar-summary__event-title-link tribe-common-anchor-thin"
	>
		<?php
		// phpcs:ignore
		echo $event->title;
		?>
	</a>
	<?php $this->template( 'summary/event/cost', [ 'event' => $event ] ); ?>
</h3>
