<?php
/**
 * View: List Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/compact/event.php
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


$event_classes = tribe_get_post_class( [ 'tribe-events-calendar-list__event', 'tribe-common-g-row', 'tribe-common-g-row--gutters' ], $event->ID );
$event_classes['tribe-events-calendar-list__event-row--featured'] = $event->featured;
?>
<article <?php tribe_classes( $event_classes ) ?>>

	<div class="tribe-events-calendar-compact__event-details tribe-common-g-col">

		<header class="tribe-events-calendar-compact__event-header">
			<?php $this->template( 'compact/event/date', [ 'event' => $event ] ); ?>
			<?php $this->template( 'compact/event/title', [ 'event' => $event ] ); ?>
			<?php $this->template( 'compact/event/cost', [ 'event' => $event ] ); ?>
		</header>

	</div>
</article>
