<div class="popup_pass">
    <div class="autodoc_register_popup popup_password">
        <div class="icon"></div>
        <a href="#" class="close_pop"></a>
        <div class="title"><?= __('new_pass'); ?></div>
        <p><?= __('fast_register'); ?></p>
        <div class="row">
            <?= Form::input('new_pass', '', ['placeholder' => __('new_pass') . '*', 'type' => 'password', 'maxlength' => Config::get('front.form_all_inputs_length')]); ?>
            <input type="button" class="show_password" id="pass-button">
        </div>
        <div class="row">
            <?= Form::input('new_pass_confirm', '', ['placeholder' => __('new_pass_confirm') . '*', 'type' => 'password']); ?>
            <input type="button" class="show_password" id="pass-button">
        </div>
        <div class="button register_submit fast">
            <?= Html::anchor('#', __('register'), [
                'onclick' => 'fbq(\'track\', \'CompleteRegistration\');',
            ]) ?>
        </div>
    </div>
</div>