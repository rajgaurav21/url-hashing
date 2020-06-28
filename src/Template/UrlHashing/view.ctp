<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UrlHashing $urlHashing
 */
?>
<div class="urlHashing view panel panel-body login-form">
    <h3><?= h('URL Shortening') ?></h3>
    <br/>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Shortened URL') ?></th>
            <td><?= h('http://localhost/news-bytes/urlHashing/shortenedUrl/' . $urlHashing->hash) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Originial URL') ?></th>
            <td style="size:100dpi"><?= h($urlHashing->original_url) ?></td>
        </tr>
    </table>
    <br/>
    <div class = "panel-body">
        <?= $this->Html->link(
            __('Shorten more links'),
            ['action' => 'index'],
            ['role' => 'button', 'class' => 'btn btn-primary btn-xs']
        )?>
    </div>
</div>
