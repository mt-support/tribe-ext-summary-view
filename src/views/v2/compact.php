<?php
/**
 * View: Compact View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/compact.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 1.0.0
 *
 * @var array    $events               The array containing the events.
 * @var array    $events_by_date       An array containing the events indexed by date.
 * @var string   $rest_url             The REST URL.
 * @var string   $rest_method          The HTTP method, either `POST` or `GET`, the View will use to make requests.
 * @var string   $rest_nonce           The REST nonce.
 * @var int      $should_manage_url    int containing if it should manage the URL.
 * @var bool     $disable_event_search Boolean on whether to disable the event search.
 * @var string[] $container_classes    Classes used for the container of the view.
 * @var array    $container_data       An additional set of container `data` attributes.
 * @var string   $breakpoint_pointer   String we use as pointer to the current view we are setting up with breakpoints.
 */

$header_classes = [ 'tribe-events-header' ];
if ( empty( $disable_event_search ) ) {
	$header_classes[] = 'tribe-events-header--has-event-search';
}

add_filter( 'tribe_format_second_date_in_range', static function() {
	return 'M j';
} );
?>
<style>
	.tribe-events-calendar-list__month-separator + .tribe-events-calendar-list__date-separator {
		display: none;
	}

	.tribe-events .tribe-events-calendar-list__date-separator::after {
		background-color: #eee;
		content: '';
		display: block;
		-webkit-box-flex: 1;
		flex: auto;
		height: 1px;
	}

	.tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-list__month-separator + .tribe-events-calendar-list__event-row {
		margin-bottom: 8px;
		margin-top: 16px;
	}

	.tribe-common .tribe-events-calendar-compact__event {
		margin-bottom: 16px;
	}

	.tribe-common .tribe-events-calendar-compact__event:last-child,
	.tribe-common--breakpoint-medium .tribe-events-calendar-compact__event {
		margin-bottom: 0;
	}

	.tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-list__event-row {
		margin-bottom: 8px;
		margin-top: 8px;
	}

	.tribe-common--breakpoint-medium article .tribe-events-calendar-compact__event-header {
		display: flex;
		flex-basis: auto;
		flex-direction: row;
		flex-wrap: nowrap;
	}

	.tribe-common--breakpoint-medium .tribe-events-calendar-compact__event-datetime-wrapper {
		display: block;
		flex-grow: 0;
		flex-shrink: 0;
		margin-bottom: 0;
		width: 230px;
	}

	.tribe-common--breakpoint-medium .tribe-events-calendar-compact__event-title {
		display: block;
		flex-grow: 0;
	}

	.tribe-common.tribe-events .tribe-events-calendar-compact__event-cost {
		margin-top: 0;
		line-height: 1.62;
	}

	.tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-compact__event-cost {
		flex-grow: 0;
		margin-left: 16px;
		margin-top: 0;
		line-height: 1.62;
	}

	.tribe-common .tribe-events-c-small-cta__price {
		border-radius: 10px;
		display: inline-block;
		margin-bottom: 0;
		margin-top: 0;
		padding: 0.2rem 1rem 0;
		background-color: #eee;
	}
</style>
<div
	<?php tribe_classes( $container_classes ); ?>
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
	data-view-rest-url="<?php echo esc_url( $rest_url ); ?>"
	data-view-rest-method="<?php echo esc_attr( $rest_method ); ?>"
	data-view-manage-url="<?php echo esc_attr( $should_manage_url ); ?>"
	<?php foreach ( $container_data as $key => $value ) : ?>
		data-view-<?php echo esc_attr( $key ) ?>="<?php echo esc_attr( $value ) ?>"
	<?php endforeach; ?>
	<?php if ( ! empty( $breakpoint_pointer ) ) : ?>
		data-view-breakpoint-pointer="<?php echo esc_attr( $breakpoint_pointer ); ?>"
	<?php endif; ?>
>
	<div class="tribe-common-l-container tribe-events-l-container">
		<?php $this->template( 'components/loader', [ 'text' => __( 'Loading...', 'the-events-calendar' ) ] ); ?>

		<?php $this->template( 'components/json-ld-data' ); ?>

		<?php $this->template( 'components/data' ); ?>

		<?php $this->template( 'components/before' ); ?>

		<header <?php tribe_classes( $header_classes ); ?>>
			<?php $this->template( 'components/messages' ); ?>

			<?php $this->template( 'components/breadcrumbs' ); ?>

			<?php $this->template( 'components/events-bar' ); ?>

			<?php $this->template( 'compact/top-bar' ); ?>
		</header>

		<?php $this->template( 'components/filter-bar' ); ?>

		<div class="tribe-events-calendar-compact">

			<?php foreach ( $events_by_date as $date_for_group => $events_data ) : ?>
				<?php $event = $events_data[0]; ?>
				<?php $this->setup_postdata( $event ); ?>
				<?php $this->template( 'compact/month-separator', [ 'events' => $events, 'event' => $event ] ); ?>
				<?php $this->template( 'compact/date-separator', [ 'events' => $events, 'event' => $event ] ); ?>
				<?php $this->template( 'compact/date-group', [ 'events_for_date' => $events_data, 'date' => $date_for_group ] ); ?>
			<?php endforeach; ?>

		</div>

		<?php $this->template( 'compact/nav' ); ?>

		<?php $this->template( 'components/ical-link' ); ?>

		<?php $this->template( 'components/after' ); ?>

	</div>
</div>

<?php $this->template( 'components/breakpoints' ); ?>
