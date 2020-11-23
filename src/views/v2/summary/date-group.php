<?php
/**
 * View: Summary View Date grouping
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/date-separator.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 1.0.0
 *
 * @var \Tribe\Utils\Date_I18n_Immutable $date         The date for the date group.
 * @var array                            $events_for_date The array of events for the date group.
 * @var \DateTimeInterface               $request_date    The request date object. This will be "today" if the user did not input any
 *                                                        date, or the user input date.
 * @var bool                             $is_past         Whether the current display mode is "past" or not.
 *
 * @see tribe_get_event() For the format of the event object.
 */

$container_classes = [ 'tribe-common-g-row', 'tribe-events-calendar-list__event-row' ];
?>
<div <?php tribe_classes( $container_classes ); ?>>

	<?php $event = $events_for_date[0]; ?>
	<?php $this->setup_postdata( $event ); ?>
	<?php $this->template( 'summary/event/date-tag', [ 'event' => $event, 'date' => $date ] ); ?>

	<div class="tribe-events-calendar-summary__event-wrapper tribe-common-g-col">
		<?php foreach ( $events_for_date as $event ) : ?>
			<?php $this->setup_postdata( $event ); ?>
			<?php $this->template( 'summary/event', [ 'event' => $event ] ); ?>
		<?php endforeach; ?>
	</div>

</div>
