var shopGiftforyouFrontend = (function ($) {

    shopGiftforyouFrontend = function (options) {
        var that = this;

        // DOM
        that.$wrapper = options['$wrapper'] || $(document.body);
        that.$spinBtn = that.$wrapper.find('.js-gift-spin-btn');
        that.$productBlock = that.$wrapper.find('.js-gift-product');
        that.$productImg = that.$wrapper.find('.js-gift-product-img');
        that.$productName = that.$wrapper.find('.js-gift-product-name');
        that.$productPrice = that.$wrapper.find('.js-gift-product-price');
        that.$productLink = that.$wrapper.find('.js-gift-product-link');
        that.$productId = that.$wrapper.find('.js-gift-product-id');
        that.$emailForm = that.$wrapper.find('.js-gift-email-form');
        that.$emailInput = that.$wrapper.find('.js-gift-email');
        that.$message = that.$wrapper.find('.js-gift-message');

        // VARS
        that.getGiftUrl = (window.giftforyouUrls && window.giftforyouUrls.getGift) || '/gift/get/';
        that.sendEmailUrl = (window.giftforyouUrls && window.giftforyouUrls.sendEmail) || '/gift/send/';

        // DYNAMIC VARS

        // INIT
        if (that.$spinBtn.length === 0) {
            console.log('Элементы плагина gift_for_You не найдены на странице');
            return;
        }

        console.log('Giftforyou plugin initialized');
        console.log('Gift URLs:', {getGiftUrl: that.getGiftUrl, sendEmailUrl: that.sendEmailUrl});
        console.log('Button found:', that.$spinBtn.length > 0);

        that.initClass();
    };

    shopGiftforyouFrontend.prototype.initClass = function () {
        var that = this;

        that.initSpin();
        that.initEmailForm();
    };

    shopGiftforyouFrontend.prototype.initSpin = function () {
        var that = this;

        that.$spinBtn.on('click', function (e) {
            e.preventDefault();
            console.log('Кнопка "Крутить" нажата');

            var $btn = $(this);
            $btn.prop('disabled', true).text('Загрузка...');
            that.$productBlock.hide();
            that.$message.hide();

            console.log('Отправка AJAX запроса на:', that.getGiftUrl);

            that.getGift($btn);
        });
    };

    shopGiftforyouFrontend.prototype.initEmailForm = function () {
        var that = this;

        that.$emailForm.on('submit', function (e) {
            e.preventDefault();

            var email = that.$emailInput.val();
            var productId = that.$productId.val();

            if (!email) {
                that.showMessage('Пожалуйста, введите email', 'error');
                return;
            }

            if (!productId) {
                that.showMessage('Ошибка: товар не выбран', 'error');
                return;
            }

            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Отправка...');
            that.$message.hide();

            that.sendEmail(email, productId, $submitBtn);
        });
    };

    shopGiftforyouFrontend.prototype.getGift = function ($btn) {
        var that = this;

        $.ajax({
            url: that.getGiftUrl,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log('AJAX успешный ответ:', response);

                if (response.status === 'fail') {
                    var errorMsg = that.parseError(response.errors, 'Не удалось получить товар');
                    console.log('Ошибка от сервера:', errorMsg);
                    that.showMessage(errorMsg, 'error');
                    $btn.prop('disabled', false).text('Крутить');
                    return;
                }

                if (response.status === 'ok' && response.data) {
                    var product = response.data;

                    if (product.image) {
                        that.$productImg.attr('src', product.image).attr('alt', product.name);
                    }
                    that.$productName.text(product.name);
                    that.$productPrice.text('Цена: ' + product.price);
                    that.$productLink.attr('href', product.url);
                    that.$productId.val(product.id);

                    that.$productBlock.slideDown();
                } else {
                    var errorMsg = 'Не удалось получить товар. Неожиданный формат ответа.';
                    console.warn('Неожиданный формат ответа:', response);
                    that.showMessage(errorMsg, 'error');
                }
                $btn.prop('disabled', false).text('Крутить');
            },
            error: function (xhr, status, error) {
                console.error('AJAX ошибка:', status, error, xhr);
                var errorMsg = 'Ошибка при загрузке товара. Попробуйте еще раз.';

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        errorMsg = that.parseError(xhr.responseJSON.errors, errorMsg);
                    } else if (xhr.responseJSON.status === 'fail') {
                        errorMsg = xhr.responseJSON.errors || errorMsg;
                    }
                }

                that.showMessage(errorMsg, 'error');
                $btn.prop('disabled', false).text('Крутить');
            }
        });
    };

    shopGiftforyouFrontend.prototype.sendEmail = function (email, productId, $submitBtn) {
        var that = this;

        $.ajax({
            url: that.sendEmailUrl,
            type: 'POST',
            data: {
                email: email,
                product_id: productId
            },
            dataType: 'json',
            success: function (response) {
                console.log('AJAX успешный ответ (email):', response);

                if (response.status === 'fail') {
                    var errorMsg = that.parseError(response.errors, 'Не удалось отправить письмо');
                    console.log('Ошибка от сервера (email):', errorMsg);
                    that.showMessage(errorMsg, 'error');
                    $submitBtn.prop('disabled', false).text('Отправить');
                    return;
                }

                if (response.status === 'ok') {
                    that.showMessage('Письмо успешно отправлено на ' + email, 'success');
                    that.$emailInput.val('');
                } else {
                    var errorMsg = 'Не удалось отправить письмо. Неожиданный формат ответа.';
                    console.warn('Неожиданный формат ответа (email):', response);
                    that.showMessage(errorMsg, 'error');
                }
                $submitBtn.prop('disabled', false).text('Отправить');
            },
            error: function (xhr, status, error) {
                console.error('AJAX ошибка:', status, error, xhr);
                var errorMsg = 'Ошибка при отправке письма. Попробуйте еще раз.';

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        errorMsg = that.parseError(xhr.responseJSON.errors, errorMsg);
                    } else if (xhr.responseJSON.status === 'fail') {
                        errorMsg = xhr.responseJSON.errors || errorMsg;
                    }
                }

                that.showMessage(errorMsg, 'error');
                $submitBtn.prop('disabled', false).text('Отправить');
            }
        });
    };

    shopGiftforyouFrontend.prototype.parseError = function (errors, defaultMsg) {
        if (!errors) {
            return defaultMsg;
        }

        if (typeof errors === 'string') {
            return errors;
        } else if (Array.isArray(errors)) {
            return errors.join(', ');
        } else if (typeof errors === 'object') {
            // Если это объект с полем message
            if (errors.message) {
                return errors.message;
            }
            // Если это массив объектов с полем message
            if (Array.isArray(errors) && errors[0] && errors[0].message) {
                return errors[0].message;
            }
            return JSON.stringify(errors);
        }

        return defaultMsg;
    };

    shopGiftforyouFrontend.prototype.showMessage = function (text, type) {
        var that = this;

        console.log('showMessage вызвана:', text, type);

        if (that.$message.length === 0) {
            console.error('Элемент сообщения не найден!');
            console.log('Сообщение:', text);
            return;
        }

        that.$message
            .removeClass('success error')
            .addClass(type)
            .text(text)
            .show();

        setTimeout(function () {
            that.$message.slideUp();
        }, 5000);
    };

    return shopGiftforyouFrontend;
})(jQuery);

jQuery(document).ready(function ($) {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery не загружен');
        return;
    }

    new shopGiftforyouFrontend({
        $wrapper: $(document.body)
    });
});
