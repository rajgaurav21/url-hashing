<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UrlHashing $urlHashing
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $urlHashing->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $urlHashing->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Url Hashing'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="urlHashing form large-9 medium-8 columns content">
    <?= $this->Form->create($urlHashing) ?>
    <fieldset>
        <legend><?= __('Edit Url Hashing') ?></legend>
        <?php
            echo $this->Form->control('hash');
            echo $this->Form->control('original_url');
            echo $this->Form->control('expiration_date', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
