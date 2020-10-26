<?php
/**
 * View: Compact View Nav Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/compact/nav.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @var string $prev_url The URL to the previous page, if any, or an empty string.
 * @var string $next_url The URL to the next page, if any, or an empty string.
 * @var string $today_url The URL to the today page, if any, or an empty string.
 *
 * @version 1.0.0
 *
 */
?>
<nav class="tribe-events-calendar-list-nav tribe-events-c-nav">
	<ul class="tribe-events-c-nav__list">
		<?php
		if ( ! empty( $prev_url ) ) {
			$this->template( 'compact/nav/prev', [ 'link' => $prev_url ] );
		} else {
			$this->template( 'compact/nav/prev-disabled' );
		}
		?>

		<?php $this->template( 'compact/nav/today' ); ?>

		<?php
		if ( ! empty( $next_url ) ) {
			$this->template( 'compact/nav/next', [ 'link' => $next_url ] );
		} else {
			$this->template( 'compact/nav/next-disabled' );
		}
		?>
	</ul>
</nav>
