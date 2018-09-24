<div class="cas_login_popup popup_login pass">
    <div class="top_info">
        <a href="#" class="close_log_on"></a>
        <div class="icon"></div>
        <div class="title"><?= __('log_on'); ?></div>
        <div class="login_form">
            <p>Введите E-mail и пароль</p>
            <form id="login_top" method="post">
                <div class="row"><input name="Email" id="login_top_email" type="text" placeholder="E-mail"></div>
                <div class="row">
                    <input type="password" name="Password" placeholder="Password">
                    <input type="button" class="show_password" id="pass-button">
                </div>
                <div class="check remember"><input name="rememberPass" type="checkbox"><label>Запомнить пароль</label></div>
                <div class="button"><a href="#" class="enter submit">Войти</a></div>
                <a href="#" class="versegen"><span>Забыл пароль</span></a>
                <p class="rm">Регистрация</p>
            </form>
        </div>
    </div>
</div>