<?php 

    $header_config = get_field('header_config', 'option');
    if (!empty($header_config['skip_to_content_link'])) {
        $skipToContentLink = $header_config['skip_to_content_link'];
        echo _tag('a', [
            'class' => 'uic-skip-to-content',
            'href' => $skipToContentLink['url'],
            'title' => $skipToContentLink['title'],
        ], [
            _tag('span', [], $skipToContentLink['title']),
            ' ',
            _tag('span', '(Alt + 0)')
        ]);
    }
?>