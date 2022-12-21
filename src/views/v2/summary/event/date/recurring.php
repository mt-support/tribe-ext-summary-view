<?php
/**
 * View: Summary View - Single Event Recurring Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/date/recurring.php
 *
 * See more documentation about our views templating system.
 *
 * Note this view uses classes from the list view event datetime to leverage those styles.
 *
 * @link http://m.tri.be/1aiy
 *
 * @since 1.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( empty( $event->recurring ) ) {
	return;
}
?>
<a
	href="<?php echo esc_url( $event->permalink_all ); ?>"
	class="tribe-events-calendar-list__event-datetime-recurring-link"
>
	<em
		class="tribe-events-calendar-list__event-datetime-recurring-icon tribe-events-calendar-summary__event-datetime-icon"
		title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
	>
		<?php $this->template( 'components/icons/recurring', [ 'classes' => [ 'tribe-events-calendar-list__event-datetime-recurring-icon-svg' ] ] ); ?>
	</em>
</a>
