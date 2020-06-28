<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UrlDetail $urlDetail
 */
?>
<div class="panel panel-body login-form">
    <?= $this->Form->create() ?>
    <div class="text-center">
        <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
        <h5 class="content-group">
            <?= __("URL Shortner") ?> 
            <small class="display-block"><?= __("Enter the original URL below") ?></small>
            </h5>
    </div>
    <fieldset>
        <legend><?= __('Add Url Detail') ?></legend>
        <?php
            echo $this->Form->control('original_url');
            echo $this->Form->control('expiration_date');
        ?>
    </fieldset>
    <div class="text-center">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
