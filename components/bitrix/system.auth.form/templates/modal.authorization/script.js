$(document).ready(function () {
	let $form = $(".auth-modal-window__form"),
		$authButton = $(".auth-modal-window__button > input")
	$authButton.click(function () { // задаем функцию при нажатиии на элемент <button>
		$form.submit(function (e) {
			$.post('', $form.serialize(), function (response) {
				if (response && response.STATUS) {
					if (response.STATUS == 'OK') {
						window.location = window.location;
					} else {
						$.each(response.MESSAGES, function (index, value) {
							if (response.MESSAGES.ERROR_AUTH.length) {
								$('.auth-modal-window__title + .auth-modal-window__error').text(value);
							} else {
								$('.auth-modal-window__title + .auth-modal-window__error').empty();
							}
							if (response.MESSAGES[index].length) {
								if ($('input[name="USER_' + index + '"] + .auth-modal-window__error').is(':empty')) {
									$('input[name="USER_' + index + '"] + .auth-modal-window__error').text(value);
									$('input[name="USER_' + index + '"]').parent().addClass('auth-modal-window__string-error')
								}
							} else {
								$('input[name="USER_' + index + '"] + .auth-modal-window__error').empty();
								$('input[name="USER_' + index + '"]').parent().removeClass('auth-modal-window__string-error')
							}
						});
					}
				}
			}, 'json');
			e.preventDefault()
			e.stopImmediatePropagation()
		});
	});
});