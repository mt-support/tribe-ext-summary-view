<?php
/**
 * View: Summary View - Single Event Date
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/date.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @since 1.0.0
 *
 * @var WP_Post                          $event      The event post object with properties added by the `tribe_get_event` function.
 * @var \Tribe\Utils\Date_I18n_Immutable $group_date The date for the date group.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 * @version 1.0.0
 */
use Tribe__Date_Utils as Dates;

$event->schedule_details;
$formatted_start_date = $event->dates->start->format( Dates::DBDATEFORMAT );
$formatted_group_date = $group_date->format( Dates::DBDATEFORMAT );
?>
<div class="tribe-common-b3 tribe-events-calendar-summary__event-datetime-wrapper">
	<time class="tribe-events-calendar-summary__event-datetime" datetime="<?php echo esc_attr( $formatted_start_date ); ?>" title="<?php echo $event->start_date . ' :: ' . $event->end_date; ?>">
		<?php if ( ! $event->multiday ) : ?>
			<span class="tribe-event-date-start"><?php echo esc_html( $event->summary_view->start_time ); ?></span> -
			<span class="tribe-event-date-end"><?php echo esc_html( $event->summary_view->end_time ); ?></span>
		<?php elseif ( ! empty( $event->summary_view ) && $event->summary_view->start_date === $formatted_group_date ) : ?>
			<span class="tribe-event-date-start"><?php echo esc_html( $event->summary_view->start_time ); ?></span>
		<?php elseif ( ! empty( $event->summary_view ) && $event->summary_view->end_date === $formatted_group_date ) : ?>
			<?php echo _x( 'to', '"to" as in "from DATE to DATE"', 'tribe-ext-summary-view' ); ?> <span class="tribe-event-date-end"><?php echo esc_html( $event->summary_view->end_time ); ?></span>
		<?php else : ?>
			<span class="tribe-event-date-start"><?php echo esc_html( __( 'All day', 'tribe-ext-summary-view' ) ); ?></span>
		<?php endif; ?>
	</time>
	<?php $this->template( 'summary/event/date/meta', [ 'event' => $event ] ); ?>
	<?php $this->template( 'summary/event/date/recurring' ); ?>
</div>
