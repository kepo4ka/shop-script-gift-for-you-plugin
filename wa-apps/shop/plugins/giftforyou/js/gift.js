(function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery не загружен');
        return;
    }

    jQuery(document).ready(function($) {
        var $spinBtn = $('.js-gift-spin-btn');
        var $productBlock = $('.js-gift-product');
        var $productImg = $('.js-gift-product-img');
        var $productName = $('.js-gift-product-name');
        var $productPrice = $('.js-gift-product-price');
        var $productLink = $('.js-gift-product-link');
        var $productId = $('.js-gift-product-id');
        var $emailForm = $('.js-gift-email-form');
        var $message = $('.js-gift-message');

        // Проверяем наличие элементов на странице
        if ($spinBtn.length === 0) {
            console.log('Элементы плагина gift_for_You не найдены на странице');
            return;
        }

        // Получаем URL для AJAX запросов из переменной, переданной из шаблона
        var getGiftUrl = (window.giftforyouUrls && window.giftforyouUrls.getGift) || '/gift/get/';
        var sendEmailUrl = (window.giftforyouUrls && window.giftforyouUrls.sendEmail) || '/gift/send/';

        console.log('Giftforyou plugin initialized');
        console.log('Gift URLs:', {getGiftUrl: getGiftUrl, sendEmailUrl: sendEmailUrl});
        console.log('Button found:', $spinBtn.length > 0);

        // Обработка клика на кнопку "Крутить"
        $spinBtn.on('click', function(e) {
            e.preventDefault();
            console.log('Кнопка "Крутить" нажата');
            var $btn = $(this);
            $btn.prop('disabled', true).text('Загрузка...');
            $productBlock.hide();
            $message.hide();

            console.log('Отправка AJAX запроса на:', getGiftUrl);

            // AJAX запрос для получения случайного товара
            $.ajax({
                url: getGiftUrl,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'ok' && response.data) {
                        var product = response.data;

                        // Заполняем данные товара
                        if (product.image) {
                            $productImg.attr('src', product.image).attr('alt', product.name);
                        }
                        $productName.text(product.name);
                        $productPrice.text('Цена: ' + product.price);
                        $productLink.attr('href', product.url);
                        $productId.val(product.id);

                        // Показываем блок с товаром
                        $productBlock.slideDown();
                    } else {
                        showMessage('Ошибка: ' + (response.errors || 'Не удалось получить товар'), 'error');
                    }
                    $btn.prop('disabled', false).text('Крутить');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX ошибка:', status, error, xhr);
                    var errorMsg = 'Ошибка при загрузке товара. Попробуйте еще раз.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = xhr.responseJSON.errors;
                    }
                    showMessage(errorMsg, 'error');
                    $btn.prop('disabled', false).text('Крутить');
                }
            });
        });

    // Обработка отправки формы email
    $emailForm.on('submit', function(e) {
        e.preventDefault();

        var email = $('.js-gift-email').val();
        var productId = $productId.val();

        if (!email) {
            showMessage('Пожалуйста, введите email', 'error');
            return;
        }

        if (!productId) {
            showMessage('Ошибка: товар не выбран', 'error');
            return;
        }

        var $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).text('Отправка...');
        $message.hide();

        // AJAX запрос для отправки письма
        $.ajax({
            url: sendEmailUrl,
            type: 'POST',
            data: {
                email: email,
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'ok') {
                    showMessage('Письмо успешно отправлено на ' + email, 'success');
                    $('.js-gift-email').val('');
                } else {
                    showMessage('Ошибка: ' + (response.errors || 'Не удалось отправить письмо'), 'error');
                }
                $submitBtn.prop('disabled', false).text('Отправить');
            },
            error: function(xhr, status, error) {
                console.error('AJAX ошибка:', status, error, xhr);
                var errorMsg = 'Ошибка при отправке письма. Попробуйте еще раз.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = xhr.responseJSON.errors;
                }
                showMessage(errorMsg, 'error');
                $submitBtn.prop('disabled', false).text('Отправить');
            }
        });
    });

    // Функция для отображения сообщений
    function showMessage(text, type) {
        if ($message.length === 0) {
            console.log('Сообщение:', text);
            return;
        }
        $message
            .removeClass('success error')
            .addClass(type)
            .text(text)
            .slideDown();

        // Автоматически скрыть сообщение через 5 секунд
        setTimeout(function() {
            $message.slideUp();
        }, 5000);
    }
    });
})();

