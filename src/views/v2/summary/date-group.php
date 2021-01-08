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
 * @var \Tribe\Utils\Date_I18n_Immutable $group_date      The date for the date group.
 * @var array                            $events_for_date The array of events for the date group.
 *                                                        date, or the user input date.
 *
 * @see tribe_get_event() For the format of the event object.
 */

$container_classes = [ 'tribe-common-g-row', 'tribe-events-calendar-list__event-row' ];
?>
<div <?php tribe_classes( $container_classes ); ?>>

	<?php $event = current( $events_for_date ); ?>
	<?php $this->setup_postdata( $event ); ?>
	<?php $this->template( 'summary/event/date-tag', [ 'event' => $event, 'group_date' => $group_date ] ); ?>

	<div class="tribe-events-calendar-summary__event-wrapper <?php if ( 1 < count( $events_for_date) ) { echo esc_attr('tribe-events-calendar-summary__event-wrapper--multi-event'); } ?> tribe-common-g-col">
		<?php foreach ( $events_for_date as $event ) : ?>
			<?php $this->setup_postdata( $event ); ?>
			<?php $this->template( 'summary/event', [ 'event' => $event, 'group_date' => $group_date ] ); ?>
		<?php endforeach; ?>
	</div>

</div>
