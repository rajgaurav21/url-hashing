<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UrlHashing $urlHashing
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Url Hashing'), ['action' => 'edit', $urlHashing->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Url Hashing'), ['action' => 'delete', $urlHashing->id], ['confirm' => __('Are you sure you want to delete # {0}?', $urlHashing->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Url Hashing'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Url Hashing'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="urlHashing view large-9 medium-8 columns content">
    <h3><?= h($urlHashing->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Hash') ?></th>
            <td><?= h($urlHashing->hash) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($urlHashing->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($urlHashing->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Expiration Date') ?></th>
            <td><?= h($urlHashing->expiration_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Original Url') ?></h4>
        <?= $this->Text->autoParagraph(h($urlHashing->original_url)); ?>
    </div>
</div>
