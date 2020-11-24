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

$formatted_start_date = $event->dates->start->format( Dates::DBDATEFORMAT );
$formatted_end_date   = $event->dates->end->format( Dates::DBDATEFORMAT );
$formatted_group_date = $group_date->format( Dates::DBDATEFORMAT );
$suppress_start_date  = $formatted_group_date === $formatted_start_date;
$is_psuedo_all_day    = $formatted_group_date !== $formatted_start_date && $formatted_group_date !== $formatted_end_date;

?>
<div class="tribe-events-calendar-summary__event-datetime-wrapper tribe-common-b2">
	<?php $this->template( 'summary/event/date/featured' ); ?>
	<time class="tribe-events-calendar-summary__event-datetime" datetime="<?php echo esc_attr( $formatted_start_date ); ?>">
		<?php
		$schedule = $event->schedule_details->value();

		if ( false === strpos( $schedule, '@' ) || $is_psuedo_all_day ) {
			$schedule = 'All day';
		} elseif ( $suppress_start_date ) {
			$schedule = preg_replace( '/^[^@]+@\s/', '', $schedule );
		}

		echo $schedule;
		?>
	</time>
	<?php $this->template( 'summary/event/date/meta', [ 'event' => $event ] ); ?>
</div>
