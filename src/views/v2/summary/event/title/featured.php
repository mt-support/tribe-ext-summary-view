<?php
/**
 * View: Summary View - Single Event Featured Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/title/featured.php
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
 *
 * @version 1.0.0
 */

if ( empty( $event->featured ) ) {
	return;
}
?>
<em
	class="tribe-events-calendar-list__event-datetime-featured-icon tribe-events-calendar-summary__event-title-icon"
	aria-label="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
	title="<?php esc_attr_e( 'Featured', 'the-events-calendar' ); ?>"
>
	<?php $this->template( 'components/icons/featured', [ 'classes' => [ 'tribe-events-calendar-list__event-title-featured-icon-svg' ] ] ); ?>
</em>
<span class="tribe-events-calendar-list__event-datetime-featured-text tribe-events-calendar-summary__event-title-featured-text tribe-common-a11y-visual-hide">
	<?php esc_html_e( 'Featured', 'the-events-calendar' ); ?>
</span>
