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

$event_date_attr     = $event->dates->start->format( Dates::DBDATEFORMAT );
$suppress_start_date = $group_date->format( Dates::DBDATEFORMAT ) === $event_date_attr;

?>
<div class="tribe-events-calendar-summary__event-datetime-wrapper tribe-common-b2">
	<?php $this->template( 'summary/event/date/featured' ); ?>
	<time class="tribe-events-calendar-summary__event-datetime" datetime="<?php echo esc_attr( $event_date_attr ); ?>">
		<?php
		$schedule = $event->schedule_details->value();

		if ( false === strpos( $schedule, '@' ) ) {
			$schedule = 'All day';
		} elseif ( $suppress_start_date ) {
			$schedule = preg_replace( '/^[^@]+@\s/', '', $schedule );
		}

		echo $schedule;
		?>
	</time>
	<?php $this->template( 'summary/event/date/meta', [ 'event' => $event ] ); ?>
</div>
