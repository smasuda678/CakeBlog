<?php
/**
  * @var \App\View\AppView $this
  */
?>
    <?= $this->Form->create($article,['class' => 'manage_edit large-10', 'type' => 'file']) ?>
    <fieldset>
        <?php

            echo $this->Form->control('title');
            echo $this->Form->control('body');
            echo $this->Form->create('picture_data', array('enctype' => 'multipart/form-da'));
            echo $this->Form->control('picture_data', ['type' => 'file', 'label' => 'picture']);
            if (isset($article->picture['data'])) {
                echo $this->Html->image("/webroot/uploads/pictures/".$article->picture['data']);
            }
        ?>


    </fieldset>

    <?= $this->Form->button(__('Submit'),['class' => 'manage_button']) ?>
    <?php $url = $this->Url->build(['action' => 'index']) ?>
    <?= $this->Form->button(__('Cancel'),['class' => 'manage_button', 'type' => 'button', 'onclick' => "location.href='$url'"]) ?>
    <?= $this->Form->end() ?>
    <?php
        if ($id !== null) {
            echo $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $article->id], [
                    'confirm' => __('Are you sure you want to delete this article ? # id:{0}', $article->id),
                    'class' => 'manage_button_delete',
                ]
            );
        }
    ?>

</div>
