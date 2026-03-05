<?php
/**
 * Tab Checklist (ACF Block)
 */

$block_id = !empty($block['anchor'])
  ? $block['anchor']
  : 'tab-checklist-' . $block['id'];

$classes = 'tab-checklist';
if (!empty($block['className'])) $classes .= ' ' . $block['className'];
if (!empty($block['align']))     $classes .= ' align' . $block['align'];

$section_label = get_field('section_label') ?: '';
$header = get_field('header') ?: '';
$text   = get_field('text') ?: '';

$tab_left_label   = get_field('tab_left_label') ?: 'Tab Left';
$tab_left_items   = get_field('tab_left_items') ?: [];
$tab_left_note    = get_field('tab_left_note') ?: '';
$tab_left_links   = get_field('tab_left_links') ?: [];

$tab_right_label  = get_field('tab_right_label') ?: 'Tab Right';
$tab_right_items  = get_field('tab_right_items') ?: [];
$tab_right_note   = get_field('tab_right_note') ?: '';
$tab_right_links  = get_field('tab_right_links') ?: [];

$uid = preg_replace('/[^a-zA-Z0-9\-_]/', '', $block_id);

$tabs_id = $uid . '--tabs';
$panel_left_id  = $uid . '--panel-left';
$panel_right_id = $uid . '--panel-right';
?>

<section id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($classes); ?> uic-section__container">
  <div class="uic-section__inner tab-checklist__inner">

    <?php if ($section_label) : ?>
      <div class="uic-section__label">
        <h2 class="uic-h2"><?php echo esc_html($section_label); ?></h2>
      </div>
    <?php endif; ?>

    <?php if ($header) : ?>
      <h3 class="tab-checklist__header uic-h3"><?php echo esc_html($header); ?></h3>
    <?php endif; ?>

    <?php if ($text) : ?>
      <div class="tab-checklist__text">
        <?php echo wp_kses_post(wpautop($text)); ?>
      </div>
    <?php endif; ?>

    <div class="tab-checklist__tabs" role="tablist" id="<?php echo esc_attr($tabs_id); ?>">
      <button
        type="button"
        class="tab-checklist__tab is-active"
        role="tab"
        aria-selected="true"
        aria-controls="<?php echo esc_attr($panel_left_id); ?>"
        data-tab-target="left"
      >
        <?php echo esc_html($tab_left_label); ?>
      </button>

      <button
        type="button"
        class="tab-checklist__tab"
        role="tab"
        aria-selected="false"
        aria-controls="<?php echo esc_attr($panel_right_id); ?>"
        data-tab-target="right"
      >
        <?php echo esc_html($tab_right_label); ?>
      </button>
    </div>

    <div class="tab-checklist__panels">

      <div
        class="tab-checklist__panel is-active"
        id="<?php echo esc_attr($panel_left_id); ?>"
        role="tabpanel"
        data-tab-panel="left"
      >
        <?php if (!empty($tab_left_items)) : ?>
          <div class="tab-checklist__accordion">
            <?php foreach ($tab_left_items as $i => $item) :
              $title       = $item['title'] ?? '';
              $inner       = $item['inner_text'] ?? '';
              $footer_note = $item['footer_note'] ?? '';
              $content_id  = $uid . '-left-' . $i;

              if ($title === '' && $inner === '' && $footer_note === '') continue;
            ?>
              <div class="tab-checklist__item">
                <button
                  type="button"
                  class="tab-checklist__itembtn"
                  aria-expanded="false"
                  aria-controls="<?php echo esc_attr($content_id); ?>"
                >
                  <span class="tab-checklist__itemtitle"><?php echo esc_html($title); ?></span>
                  <span class="tab-checklist__chev" aria-hidden="true"></span>
                </button>

                <div
                  id="<?php echo esc_attr($content_id); ?>"
                  class="tab-checklist__itemcontent"
                  hidden
                >
                  <?php if (!empty($inner)) : ?>
                    <div class="tab-checklist__itemtext">
                      <?php echo wp_kses_post($inner); ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($footer_note)) : ?>
                    <hr class="tab-checklist__divider" aria-hidden="true" />
                    <div class="tab-checklist__footer-note">
                      <?php echo wp_kses_post(wpautop($footer_note)); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($tab_left_note)) : ?>
          <div class="tab-checklist__note">
            <?php echo wp_kses_post(wpautop($tab_left_note)); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($tab_left_links)) : ?>
          <div class="tab-checklist__links">
            <?php foreach ($tab_left_links as $row) :
              $link = $row['link'] ?? null;
              if (!is_array($link) || empty($link['url'])) continue;

              $url    = $link['url'];
              $label  = !empty($link['title']) ? $link['title'] : 'Learn more';
              $target = !empty($link['target']) ? $link['target'] : '';
            ?>
              <a
                class="tab-checklist__link"
                href="<?php echo esc_url($url); ?>"
                <?php echo $target ? 'target="' . esc_attr($target) . '" rel="noopener noreferrer"' : ''; ?>
              >
                <?php echo esc_html($label); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div
        class="tab-checklist__panel"
        id="<?php echo esc_attr($panel_right_id); ?>"
        role="tabpanel"
        data-tab-panel="right"
        hidden
      >
        <?php if (!empty($tab_right_items)) : ?>
          <div class="tab-checklist__accordion">
            <?php foreach ($tab_right_items as $i => $item) :
              $title       = $item['title'] ?? '';
              $inner       = $item['inner_text'] ?? '';
              $footer_note = $item['footer_note'] ?? '';
              $content_id  = $uid . '-right-' . $i;

              if ($title === '' && $inner === '' && $footer_note === '') continue;
            ?>
              <div class="tab-checklist__item">
                <button
                  type="button"
                  class="tab-checklist__itembtn"
                  aria-expanded="false"
                  aria-controls="<?php echo esc_attr($content_id); ?>"
                >
                  <span class="tab-checklist__itemtitle"><?php echo esc_html($title); ?></span>
                  <span class="tab-checklist__chev" aria-hidden="true"></span>
                </button>

                <div
                  id="<?php echo esc_attr($content_id); ?>"
                  class="tab-checklist__itemcontent"
                  hidden
                >
                  <?php if (!empty($inner)) : ?>
                    <div class="tab-checklist__itemtext">
                      <?php echo wp_kses_post($inner); ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($footer_note)) : ?>
                    <hr class="tab-checklist__divider" aria-hidden="true" />
                    <div class="tab-checklist__footer-note">
                      <?php echo wp_kses_post(wpautop($footer_note)); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($tab_right_note)) : ?>
          <div class="tab-checklist__note">
            <?php echo wp_kses_post(wpautop($tab_right_note)); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($tab_right_links)) : ?>
          <div class="tab-checklist__links">
            <?php foreach ($tab_right_links as $row) :
              $link = $row['link'] ?? null;
              if (!is_array($link) || empty($link['url'])) continue;

              $url    = $link['url'];
              $label  = !empty($link['title']) ? $link['title'] : 'Learn more';
              $target = !empty($link['target']) ? $link['target'] : '';
            ?>
              <a
                class="tab-checklist__link"
                href="<?php echo esc_url($url); ?>"
                <?php echo $target ? 'target="' . esc_attr($target) . '" rel="noopener noreferrer"' : ''; ?>
              >
                <?php echo esc_html($label); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>