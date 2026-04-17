<?php
/**
 * CTA Section Block
 */

$heading       = get_field('heading') ?: 'CONGRATS!';
$text          = get_field('text');
$cta_primary   = get_field('cta_primary');
$cta_secondary = get_field('cta_secondary');

$block_id = 'cta-section-' . $block['id'];

$classes = 'cta-section';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
	<div class="cta-section__inner">
		<?php if ( $heading ) : ?>
			<h2 class="cta-section__heading">
				<?php echo esc_html( $heading ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<div class="cta-section__text">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $cta_primary || $cta_secondary ) : ?>
			<div class="cta-section__actions">
				<?php if ( $cta_primary ) : ?>
					<a
						class="cta-section__link"
						href="<?php echo esc_url( $cta_primary['url'] ); ?>"
						<?php echo ! empty( $cta_primary['target'] ) ? 'target="' . esc_attr( $cta_primary['target'] ) . '"' : ''; ?>
					>
						<?php echo esc_html( $cta_primary['title'] ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $cta_secondary ) : ?>
					<a
						class="cta-section__link"
						href="<?php echo esc_url( $cta_secondary['url'] ); ?>"
						<?php echo ! empty( $cta_secondary['target'] ) ? 'target="' . esc_attr( $cta_secondary['target'] ) . '"' : ''; ?>
					>
						<?php echo esc_html( $cta_secondary['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>