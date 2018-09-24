<?= Form::open(['action' => Uri::create('basket', [], [], HTTPS_ENABLED), 'method' => 'post', 'id' => 'register_form', 'class' => 'clickr-form']); ?>

    <div class="popup_reg">
        <div class="autodoc_register_popup popup_register">
            <div class="icon"></div>
            <a href="#" class="close_pop"></a>
            <div class="title"><?= __('login_as_new_customer'); ?></div>
            <p><?= __('fast_register'); ?></p>
            <?= Form::hidden('express', 1); ?>
            <?= Form::hidden('fast_register', 1); ?>
            <div class="anrede sex">
                <span><?=__('salutation'); ?>*:&nbsp;</span>
                <?= Form::radio('rAnrede', 'Herr', true, ['id' => 'rAnrede_mr', 'class' => 'checkbox field_Herr']); ?>
                &nbsp;<label for="rAnrede_mr"><?=__('mr'); ?></label>
                <?= Form::radio('rAnrede', 'Frau', false, ['id' => 'rAnrede_mrs', 'class' => 'checkbox field_Frau']); ?>
                &nbsp;<label for="rAnrede_mrs"><?=__('mrs'); ?></label>
                <?= Form::radio('rAnrede', 'Firma', false, ['id' => 'rAnrede_company', 'class' => 'checkbox field_Firma company']); ?>
                &nbsp;<label for="rAnrede_company"><?=__('company'); ?></label>
            </div>
            <div class="row">
                <?= Form::input('rVorname', '', ['placeholder' => __('cus_name') . '*', 'maxlength' => Config::get('front.form_all_inputs_length')]); ?>
            </div>
            <div class="row">
                <?= Form::input('rName', '', ['id' => 'rName', 'placeholder' => __('cus_last_name') . '*', 'maxlength' => Config::get('front.form_all_inputs_length')]); ?>
            </div>
            <div class="row">
                <?= Form::input('Email', '', ['id' => 'email', 'placeholder' => __('cus_email') . '*', 'class' => 'inp_text']); ?>
            </div>
            <?= Utils\Helpers\Common::privacyPolicyCheckbox('privacy_policy', null, null, 'yes', ['id' => 'privacy_policy_header_modal']); ?>
            <?= Utils\Helpers\Common::subscriptionAcceptCheckbox(
                "isNewsSubscribe",
                "isSubscribe_header_modal",
                null,
                null,
                'yes',
                ['div_class' => 'newsletter_label subscribe_checkbox', 'text' => strip_tags(__('get_newsletters'))]
            ); ?>
            <div class="button">
                <a href="#" class="register_step"><?=__('create_password'); ?></a>
            </div>
            <?php echo ViewModel::forge('includes/popupnetworks'); ?>
        </div>
    </div>

<?= Form::close(); ?>
