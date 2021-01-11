<?php
/**
 * View: Summary View Date separator
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
 * @var \Tribe\Utils\Date_I18n_Immutable $group_date The date for the date group.
 * @var array                            $events     The array of events for the date group.
 * @var WP_Post                          $event      The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

?>
<div class="tribe-events-calendar-list__date-separator">
	<time
		class="tribe-common-h7 tribe-common-h6--min-medium tribe-common-h--alt tribe-events-calendar-list__date-separator-text"
		datetime="<?php echo esc_attr( $group_date->format( 'Y-m-d' ) ); ?>"
	>
	</time>
</div>
