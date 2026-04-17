<?php
/**
 * Two Column Cards (ACF Block) – render template
 *
 * Fields:
 * - section_label (text)
 * - heading (text)
 * - cards (repeater of max 2):
 * - image (image array)
 * - title (text)
 * - text (textarea)
 * - link (link array)
 */

$block_id = !empty($block['anchor'])
  ? $block['anchor']
  : 'two-col-cards-' . $block['id'];

$classes = 'two-col-cards';
if (!empty($block['className'])) $classes .= ' ' . $block['className'];
if (!empty($block['align'])) $classes .= ' align' . $block['align'];

$section_label = get_field('section_label') ?: '';
$heading       = get_field('heading') ?: '';
$cards         = get_field('cards') ?: [];

$cards = array_slice($cards, 0, 2);

$card_1 = $cards[0] ?? null;
$card_2 = $cards[1] ?? null;

// Card 1 fields
$img_1    = $card_1['image'] ?? null;
$title_1  = $card_1['title'] ?? '';
$text_1   = $card_1['text'] ?? '';
$link_1   = $card_1['link'] ?? null;
$url_1    = is_array($link_1) && !empty($link_1['url']) ? $link_1['url'] : '';
$label_1  = is_array($link_1) && !empty($link_1['title']) ? $link_1['title'] : '';
$target_1 = is_array($link_1) && !empty($link_1['target']) ? $link_1['target'] : '';
if ($url_1 && !$label_1) $label_1 = 'Learn more';

// Card 2 fields
$img_2    = $card_2['image'] ?? null;
$title_2  = $card_2['title'] ?? '';
$text_2   = $card_2['text'] ?? '';
$link_2   = $card_2['link'] ?? null;
$url_2    = is_array($link_2) && !empty($link_2['url']) ? $link_2['url'] : '';
$label_2  = is_array($link_2) && !empty($link_2['title']) ? $link_2['title'] : '';
$target_2 = is_array($link_2) && !empty($link_2['target']) ? $link_2['target'] : '';
if ($url_2 && !$label_2) $label_2 = 'Learn more';
?>

<section id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($classes); ?> uic-section__container">
  <div class="uic-section__inner two-col-cards__inner">

    <?php if ($section_label) : ?>
      <div class="uic-section__label">
        <h2 class="uic-h2" id="<?= sanitize_title($section_label) ?>"><?php echo esc_html($section_label); ?></h2>
      </div>
    <?php endif; ?>

    <?php if ($heading) : ?>
      <h3 class="two-col-cards__title uic-h3">
        <?php echo esc_html($heading); ?>
      </h3>
    <?php endif; ?>

    <?php if ($card_1 || $card_2) : ?>
      <div class="two-col-cards__grid" role="list">

        <?php if ($card_1) : ?>
          <article class="two-col-cards__card" role="listitem">
            <div class="two-col-cards__media">
              <?php if (!empty($img_1['ID'])) : ?>
                <?php
                echo wp_get_attachment_image(
                  $img_1['ID'],
                  'large',
                  false,
                  [
                    'class' => 'two-col-cards__img',
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]
                );
                ?>
              <?php else : ?>
                <div class="two-col-cards__img two-col-cards__img--placeholder" aria-hidden="true"></div>
              <?php endif; ?>
            </div>

            <div class="two-col-cards__content">
              <?php if ($title_1) : ?>
                <h3 class="two-col-cards__card-title"><?php echo esc_html($title_1); ?></h3>
              <?php endif; ?>

              <?php if ($text_1) : ?>
                <div class="two-col-cards__text">
                  <?php echo wp_kses_post(wpautop($text_1)); ?>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endif; ?>

        <?php if ($card_2) : ?>
          <article class="two-col-cards__card" role="listitem">
            <div class="two-col-cards__media">
              <?php if (!empty($img_2['ID'])) : ?>
                <?php
                echo wp_get_attachment_image(
                  $img_2['ID'],
                  'large',
                  false,
                  [
                    'class' => 'two-col-cards__img',
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]
                );
                ?>
              <?php else : ?>
                <div class="two-col-cards__img two-col-cards__img--placeholder" aria-hidden="true"></div>
              <?php endif; ?>
            </div>

            <div class="two-col-cards__content">
              <?php if ($title_2) : ?>
                <h3 class="two-col-cards__card-title"><?php echo esc_html($title_2); ?></h3>
              <?php endif; ?>

              <?php if ($text_2) : ?>
                <div class="two-col-cards__text">
                  <?php echo wp_kses_post(wpautop($text_2)); ?>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endif; ?>

      </div>
    <?php endif; ?>

    <?php if ($url_1 || $url_2) : ?>
      <div class="two-col-cards__actions">
        <?php if ($url_1) : ?>
          <a
            class="two-col-cards__link"
            href="<?php echo esc_url($url_1); ?>"
            <?php echo $target_1 ? 'target="' . esc_attr($target_1) . '" rel="noopener noreferrer"' : ''; ?>
          >
            <?php echo esc_html($label_1); ?>
          </a>
        <?php endif; ?>

        <?php if ($url_2) : ?>
          <a
            class="two-col-cards__link"
            href="<?php echo esc_url($url_2); ?>"
            <?php echo $target_2 ? 'target="' . esc_attr($target_2) . '" rel="noopener noreferrer"' : ''; ?>
          >
            <?php echo esc_html($label_2); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</section>