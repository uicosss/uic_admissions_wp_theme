<?php
/**
 * Tab Checklist (Swappable Sets - Full Version)
 */

$block_id = !empty($block['anchor']) ? $block['anchor'] : 'tab-checklist-' . $block['id'];
$classes  = 'tab-checklist ' . ($block['className'] ?? '') . ($block['align'] ? ' align' : '');

// 1. Get Top-Level Fields (Restored)
$section_label = get_field('section_label') ?: '';
$header        = get_field('header') ?: '';
$text          = get_field('text') ?: ''; // The intro text field
$orientation   = get_field('orientation') ?: 'default';

// 2. Get the Sets
$set_a_label = get_field('set_a_label') ?: 'Set A';
$set_a_items = get_field('set_a_items') ?: [];
$set_a_note  = get_field('set_a_note') ?: '';
$set_a_links = get_field('set_a_links') ?: [];

$set_b_label = get_field('set_b_label') ?: 'Set B';
$set_b_items = get_field('set_b_items') ?: [];
$set_b_note  = get_field('set_b_note') ?: '';
$set_b_links = get_field('set_b_links') ?: [];

// 3. Orientation Logic
if ($orientation === 'flipped') {
    $left_data  = ['label' => $set_b_label, 'items' => $set_b_items, 'note' => $set_b_note, 'links' => $set_b_links];
    $right_data = ['label' => $set_a_label, 'items' => $set_a_items, 'note' => $set_a_note, 'links' => $set_a_links];
} else {
    $left_data  = ['label' => $set_a_label, 'items' => $set_a_items, 'note' => $set_a_note, 'links' => $set_a_links];
    $right_data = ['label' => $set_b_label, 'items' => $set_b_items, 'note' => $set_b_note, 'links' => $set_b_links];
}

$uid = preg_replace('/[^a-zA-Z0-9\-_]/', '', $block_id);
?>

<section id="<?= esc_attr($block_id); ?>" class="<?= esc_attr($classes); ?> uic-section__container">
  <div class="uic-section__inner tab-checklist__inner">

    <?php if ($section_label) : ?>
      <div class="uic-section__label">
        <h2 class="uic-h2" id="<?= sanitize_title($section_label) ?>"><?= esc_html($section_label); ?></h2>
      </div>
    <?php endif; ?>

    <?php if ($header) : ?>
      <h3 class="tab-checklist__header uic-h3"><?= esc_html($header); ?></h3>
    <?php endif; ?>

    <?php if ($text) : ?>
      <div class="tab-checklist__text">
        <?= wp_kses_post(wpautop($text)); ?>
      </div>
    <?php endif; ?>

    <div class="tab-checklist__tabs" role="tablist">
      <button type="button" class="tab-checklist__tab is-active" role="tab" aria-selected="true" data-tab-target="left">
        <?= esc_html($left_data['label']); ?>
      </button>
      <button type="button" class="tab-checklist__tab" role="tab" aria-selected="false" data-tab-target="right">
        <?= esc_html($right_data['label']); ?>
      </button>
    </div>

    <div class="tab-checklist__panels">

      <div class="tab-checklist__panel is-active" data-tab-panel="left">
        <?php render_tab_content($left_data, $uid . '-left'); ?>
      </div>

      <div class="tab-checklist__panel" data-tab-panel="right" hidden>
        <?php render_tab_content($right_data, $uid . '-right'); ?>
      </div>

    </div>
  </div>
</section>
