<?php
$feedback_config = get_field('feedback_config', 'option');
if (!$feedback_config['enabled']) return;

if (!empty($_POST['gform_submit'])) { 
	?>
	<script>
		window.__UIC_FEEDBACK__ = true;
	</script>
	<?php
}

?>

<div class="uic-feedback uic-section uic-feedback--collapsed">
	<div href="#" class="uic-feedback__toggle"  title="Add feedback">
		<span class="uic-feedback__toggle__text">
			Add Feedback
		</span>
		<svg xmlns:xlink="http://www.w3.org/1999/xlink" aria-label="Toggle Feedback Form" alt="Toggle Feedback Form" class="uic-feedback__toggle__arrow">
			<title>Toggle Feedback Form</title>
			<use xlink:href="<?= THEME_ASSET_BASE ?>/images/back-to-top-arrow.svg#uic-back-to-top__arrow" />
		</svg>
	</div>
	<div class="uic-section__container">
		<div class="uic-section__inner">
			<div class="feedback-form gf-form-<?= $feedback_config['form_id']; ?>">
				<div class="feedback-form__content">
					<?= do_shortcode('[gravityform id="'.$feedback_config['form_id'].'" title="true"]') ?>
				</div>
			</div>
		</div>
	</div>
</div>