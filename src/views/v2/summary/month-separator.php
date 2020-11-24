<?php
/**
 * View: Summary View Month separator
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/month-separator.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 1.0.0
 *
 * @var \Tribe\Utils\Date_I18n_Immutable $date The date for the date group.
 * @var array              $events             The array of events for the date group.
 * @var WP_Post            $event              The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

use Tribe__Date_Utils as Dates;

if ( empty( $month_transition[ $date->format( Dates::DBDATEFORMAT ) ] ) ) {
	return;
}
?>
<div class="tribe-events-calendar-list__month-separator">
	<time
		class="tribe-events-calendar-list__month-separator-text tribe-common-h7 tribe-common-h6--min-medium tribe-common-h--alt"
		datetime="<?php
		echo esc_attr( $date->format( 'Y-m' ) ); ?>"
	>
		<?php echo esc_html( $date->format_i18n( 'F Y' ) ); ?>
	</time>
</div>