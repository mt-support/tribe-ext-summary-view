<?php
/**
 * View: Summary View - Single Event Virtual Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/summary/event/title/virtual.php
 *
 * See more documentation about our views templating system.
 *
 * Note this view uses classes from the virtual events plugin to leverage those styles.
 *
 * @link http://m.tri.be/1aiy
 *
 * @since 1.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

// Don't print anything when this event is not virtual.
if ( ! $event->virtual || ! $event->virtual_show_on_views ) {
	return;
}

$virtual_label = tribe_get_virtual_label();
$virtual_event_label = tribe_get_virtual_event_label_singular();
?>
<em
	class="tribe-events-virtual-virtual-event__icon tribe-events-calendar-summary__event-title-icon"
	aria-label="<?php echo esc_attr( $virtual_label ); ?>"
	title="<?php echo esc_attr( $virtual_label ); ?>"
>
	<?php $this->template( 'components/icons/virtual', [ 'classes' => [ 'tribe-events-virtual-virtual-event__icon-svg' ] ] ); ?>
</em>
<span class="tribe-events-virtual-virtual-event__text tribe-common-a11y-visual-hide">
	<?php echo esc_html( $virtual_event_label ); ?>
</span>
