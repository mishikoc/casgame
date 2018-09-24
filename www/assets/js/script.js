$(document).ready(function () {
    $(document).on('click', '.rs_pass .close_remember_pass', function () {
        $('.rs_pass.pass-recovery').remove();
        $('.cas_login_popup .login_form').show();

        return false;
    });

    $(document).on('click', '.cas_login_popup .versegen', function () {
        $('.cas_login_popup .login_form').hide();
        $('.cas_login_popup').addClass('recovery');
        $('.close_log_on').hide();
        $.post(
            '/ajax/show.html',
            {'location': 'password_recovery'},
            function (html) {
                $('.cas_login_popup').append(html);
            },
            'html'
        );
    });
    $(document).on('click', '.close_remember_pass', function () {
        $('.cas_login_popup').removeClass('recovery');
        $('.close_log_on').show();
    });
    $(document).on('click', '.close_log_on', function () {
        $('.cas_login_popup, .overlay.black').hide();
    });
    $(document).on('click', '.close_pop', function () {
        $('.popup_reg, .overlay.black').hide();
        $('.popup_pass, .overlay.black').hide();
    });
    $('.user_panel .main.auth a').on('click', function (e) {
        e.preventDefault();

        $('#login_panel_show').slideToggle('fast');
    });

    $('a.show_login_popup').on('click', function (e) {
        e.preventDefault();
        googleYoloInit();
        var loginForm = $('.cas_login_popup.popup_login.pass');

        if (loginForm.hasClass('recovery')) {
            $('.rs_pass.pass-recovery', loginForm).remove();
            loginForm.removeClass('recovery');
            $('.top_info .close_log_on', loginForm).css('display', 'block');
        }

        if (!$(".cas_login_popup").length) {
            $.post(
                '/ajax/login.html',
                {'location': 'login'},
                function (html) {
                    $('body').append(html);
                },
                'html'
            );
        }
        $('.popup_login, .login_form, .overlay.black').show();
    });

    $('.popup_login .close').on('click', function (e) {
        e.preventDefault();
        $('.popup_login, .overlay.black').hide();
    });

    $(document).on('click', '.show_register_popup, .show_register_popup_event', function (e) {
        e.preventDefault();
        $('.popup_login').hide();
        $('#popup_update').hide();
        var loginForm = $('.cas_login_popup.popup_login.pass');
        var regForm = $('#register_form');

        if (loginForm.hasClass('recovery')) {
            $('.rs_pass.pass-recovery', loginForm).remove();
            loginForm.removeClass('recovery');
            $('.top_info .close_log_on', loginForm).css('display', 'block');
        }

        if (!regForm.length) {
            $.post(
                '/ajax/login.html',
                {'location': 'register_modal'},
                function (html) {
                    $('body').append(html);

                    if (loginForm.length) {
                        loginForm.hide();
                    }
                },
                'html'
            );
        }

        $(".popup_reg").show();
        $('.overlay.black').show();
    });

    $('.popup_reg .close').on('click', function (e) {
        e.preventDefault();
        $('.popup_reg, .overlay.black').hide();
    });

    $("body").on('click', '.overlay.black', function () {
        $('.popup_reg, .popup_pass, .new_popup, .cas_login_popup.popup_login, .free_delivery_close_popap, ' +
            '.free_delivery, .selected_car_info_popap, .info-car-popup, .kba_popup_example, .rs_pass, .overlay.black, .popup_zum, ' +
            '.returnsProductsData, .ex_photo_add_photo, .popup_ndel, .popup-available').hide();
        if (!$.isEmptyObject($('#login_top')[0])) {
            $('#login_top')[0].reset();
        }
        if ($('.popup-retoure').length) {
            $('.popup-retoure').remove();
        }
        var select = $('.returnOrders .member_popup select');
        if (select.length > 0) select.ikSelect('hide_dropdown');
        resetPopupData("#top-select-popup");
    });

    $(document).on('click', '.register_step', function (e) {
        e.preventDefault();
        $.post(
            '/ajax/profile/resgister_step_one',
            $("#register_form").serialize(),
            function (response) {
                if (response.errors) {
                    getPopup(response.errors);
                    var hasField = typeof response.fields !== 'undefined' && typeof response.fields.privacy_policy !== 'undefined';
                    if (hasField && response.fields.privacy_policy) {
                        $('#register_form .privacy_policy_checkbox').addClass('register_checkbox_error');
                    }
                } else {
                    $('.popup_reg').hide();

                    $.post(
                        '/ajax/login.html',
                        {'location': 'register_password'},
                        function (html) {
                            $('#register_form').append(html);
                        },
                        'html'
                    );
                }
            },
            'json'
        );
    });

    $(".login").live('click', function (event) {
        event.preventDefault();
        if ($("#login_panel:visible").length) {
            $("#login_panel").fadeOut();
        } else {
            $("#login_panel").fadeIn();
        }
    });
});
