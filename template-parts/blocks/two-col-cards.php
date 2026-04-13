<?php
/**
 * Two Column Cards (ACF Block) – render template
 *
 * Fields:
 * - section_label (text)
 * - heading (text)
 * - cards (repeater of 2):
 *   - image (image array)
 *   - title (text)
 *   - text (textarea)
 *   - link (link array)
 */

if (defined('WP_DEBUG') && WP_DEBUG) {
  // Shows up in View Source so you can confirm the template ran
  echo "\n<!-- two-col-cards render.php called -->\n";
}

$block_id = !empty($block['anchor'])
  ? $block['anchor']
  : 'two-col-cards-' . $block['id'];

$classes = 'two-col-cards';
if (!empty($block['className'])) $classes .= ' ' . $block['className'];
if (!empty($block['align']))     $classes .= ' align' . $block['align'];

$section_label = get_field('section_label') ?: '';
$heading       = get_field('heading') ?: '';
$cards         = get_field('cards') ?: [];

// Hard cap at 2 (defensive)
$cards = array_slice($cards, 0, 2);
?>

<section id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($classes); ?> uic-section__container">
  <div class="uic-section__inner two-col-cards__inner">
    <?php if ($section_label) : ?>
    <div class="uic-section__label">
    <h2 class="uic-h2"><?php echo esc_html($section_label); ?></h2>
    </div>
    <?php endif; ?>

    <?php if ($heading) : ?>
    <h3 class="two-col-cards__title uic-h3">
        <?php echo esc_html($heading); ?>
    </h3>
    <?php endif; ?>

    <?php if (!empty($cards)) : ?>
      <div class="two-col-cards__grid" role="list">
        <?php foreach ($cards as $card) :
          $img   = $card['image'] ?? null;
          $t     = $card['title'] ?? '';
          $txt   = $card['text'] ?? '';
          $link  = $card['link'] ?? null;

          $url    = is_array($link) && !empty($link['url']) ? $link['url'] : '';
          $label  = is_array($link) && !empty($link['title']) ? $link['title'] : '';
          $target = is_array($link) && !empty($link['target']) ? $link['target'] : '';

          if ($url && !$label) $label = 'Learn more';
        ?>
          <article class="two-col-cards__card" role="listitem">
            <div class="two-col-cards__media">
              <?php if (!empty($img['ID'])) : ?>
                <?php
                  echo wp_get_attachment_image(
                    $img['ID'],
                    'large',
                    false,
                    [
                      'class' => 'two-col-cards__img',
                      'loading' => 'lazy',
                      'decoding' => 'async'
                    ]
                  );
                ?>
              <?php else : ?>
                <div class="two-col-cards__img two-col-cards__img--placeholder" aria-hidden="true"></div>
              <?php endif; ?>
            </div>

            <div class="two-col-cards__content">
              <?php if ($t) : ?>
                <h3 class="two-col-cards__card-title"><?php echo esc_html($t); ?></h3>
              <?php endif; ?>

              <?php if ($txt) : ?>
                <div class="two-col-cards__text">
                  <?php echo wp_kses_post(wpautop($txt)); ?>
                </div>
              <?php endif; ?>

              <?php if ($url) : ?>
                <a class="two-col-cards__link"
                   href="<?php echo esc_url($url); ?>"
                   <?php echo $target ? 'target="' . esc_attr($target) . '" rel="noopener noreferrer"' : ''; ?>>
                  <?php echo esc_html($label); ?>
                </a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>