<div class="uic-accordion-block">
	<div class="accordion-container">
		<details class="wysiwyg-container" <?php echo get_field('default_to_open') ? 'open' : ''; ?>>
			<summary>
				<h2 class="uic-text body-reg"><?php echo esc_html(get_field('heading')); ?></h2>
			</summary>
			<div class="wysiwyg-content uic-text body-sm">
				<?php echo get_field('content'); ?>

				<?php
				if(get_field('footnote')):
					echo '<hr>';
					echo '<p class="uic-accordion__footnote">';
					echo get_field('footnote');
					echo '</p>';
				endif;
				?>
			</div>
		</details>
	</div>
</div>
