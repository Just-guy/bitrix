BX.namespace('BX.JCWebForm');

(function () {
	'use strict';

	BX.JCWebForm = {
		init: function (parameters) {
			this.result = parameters.result;
			this.form = document.querySelector('[name=' + this.result['FORM_NAME']);
			this.webFormId = this.form.querySelector('[name="WEB_FORM_ID"]').value;
			this.formButton = this.form.querySelector('[type="submit"]');
			this.phoneField = this.form.querySelector('.callback-form__phone-field input');
			this.pathToAjax = this.result.PATH_TO_AJAX;
			this.error = false;
			this.arrayInputs = this.result.INPUTS;
			this.basket = this.result.BASKET;

			this.setMaskForPhone(this.phoneField);
			this.eventActivation();
			this.clearForm(this.arrayInputs);

			BX.bind(this.form, 'submit', BX.proxy(this.send, this));
		},

		send: function (event) {
			let fieldValues;

			this.error = false;
			fieldValues = this.formFieldValues(this.arrayInputs);
			this.shoppingCartValidation();
			BX.PreventDefault(event);
			if (this.error == true) return false;

			BX.ajax.submitAjax(this.form, {
				url: this.pathToAjax,
				method: 'POST',
				dataType: 'json',
				data: {
					fieldValues: fieldValues,
					webFormId: this.webFormId,
					arrayInputs: this.arrayInputs
				},
				onsuccess: function (data, response) {
					let object = BX.JCWebForm,
						form = object.form, answer,
						responseClass = "form-result__success",
						isError = data.ERROR != undefined && data.ERROR.length > 0,
						message = BX.message('FORM_TITLE_SUCCESS'),
						description = BX.message('FORM_DESCRIPTION_SUCCESS'),
						icon = '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.4167 27.0833L18.7501 35.4167L39.5834 14.5833" stroke="#E85231" stroke-width="4.6875" stroke-linecap="round" stroke-linejoin="round"/></svg>';

					if (form.parentNode.querySelector('.form-result')) form.parentNode.querySelector('.form-result').remove();
					form.classList.add('disabled', 'success');

					if (isError) {
						responseClass = "form-result__error";
						message = data.ERROR;
						icon = false;
					} else {
						let buttonsDelete = document.querySelectorAll(".product-card__delete");
						buttonsDelete.forEach((item, index, array) => {
							item.click();
						});
					}

					answer = new BX.PopupWindow(
						'form-result',
						null,
						{
							content: '<div class="form-result__shell ' + responseClass + '">' +
								(icon ? '<div class="form-result__icon-block">' + icon + '</div>' : '') +
								'<div class="form-result__information-block">' +
								'<h2 class="form-result__title">' + message + '</h2>' +
								(!isError ? '<p class="form-result__decription">' + description + '</p>' : '') +
								'</div>' +
								(!isError ? '<a href="/catalog/" class="form-result__button" target="_blank">Перейти в каталог</a>' : '') +
								'</div>',
							zIndex: 0,
							autoHide: true,
							offsetTop: 1,
							offsetLeft: 0,
							lightShadow: true,
							closeIcon: true,
							closeByEsc: true,
							overlay: { backgroundColor: 'black', opacity: '80' },
						}
					);

					answer.show();

					BX.JCWebForm.clearForm(data.ARRAY_INPUTS);
					BX.JCWebForm.basket = false;
				},
				onfailure: function (data, response, t, a, e) {
					let form = BX.JCWebForm.form, answer;

					answer = new BX.PopupWindow(
						'form-result',
						null,
						{
							content: '<div class="form-result__shell form-result__error">' +
								'<h3>' + BX.message('FORM_DATA_FALSE') + '</h3>' +
								'</div>',
							zIndex: 0,
							autoHide: true,
							offsetTop: 1,
							offsetLeft: 0,
							lightShadow: true,
							closeIcon: true,
							closeByEsc: true,
							overlay: { backgroundColor: 'black', opacity: '80' },
						}
					);

					answer.show();
				}
			});
		},

		setMaskForPhone: function (field) {
			let masked;

			masked = new BX.MaskedInput({
				mask: '+7 999 999 99 99',
				input: field,
				placeholder: '',
				isDataInputClean: true,
				isHoldOverInputValueInit: false,
				dataInput: {},
				onDataInputChange: function (clearString, string) {
					if (!this.input.node.parentNode.classList.contains('callback-form__field-not-empty')) {
						this.input.node.parentNode.classList.add('callback-form__field-not-empty');
					}
				},
				onDataInputInitValue: function () {

				}
			});
		},

		formFieldValues: function (arrayField) {
			let field, resultValue = [];

			for (var index in arrayField) {
				field = document.querySelector("[name='" + arrayField[index].DATA_NAME
					+ "']");

				if (arrayField[index].TYPE != 'hidden') this.fieldValidation(field);

				debugger
				resultValue[arrayField[index].DATA_NAME] = field.value;
			};

			return resultValue;
		},

		fieldValidation: function (field) {
			let message, validation, type, pattern;

			type = field.dataset.validation;
			pattern = new RegExp(field.dataset.pattern, "gmi");
			if (field.dataset.pattern != undefined && field.dataset.pattern.length != 0) validation = pattern.test(field.value);

			if (type == 'tel' && field.value.length > 0 && validation == false) {
				message = 'FORM_REQUIRED_TELEPHONE';
			}

			if (type == 'email' && field.value.length > 0 && validation == false) {
				message = 'FORM_REQUIRED_EMAIL';
			}

			if (field.value.length == '' || validation === false) {
				this.showErrorForField(field, message);
				this.error = true;
			} else {
				this.hideErrorForField(field);
			}
		},

		showErrorForField: function (element, message = 'FORM_REQUIRED_FIELDS') {
			let errorElement = element.parentElement.querySelector('.form-error');

			if (errorElement != null && errorElement.classList.contains('form-error')) {
				errorElement.innerText = BX.message(message);
				return false;
			}

			if (message.length == 0) message = 'FORM_REQUIRED_FIELDS';

			element.parentElement.prepend(BX.create('SPAN', { props: { className: 'form-error' }, text: BX.message(message) }));
			element.classList.add('form-error-field');
		},


		hideErrorForField: function (element) {
			let errorElement = element.parentElement.querySelector('.form-error');

			if (errorElement == null) return false;
			if (errorElement.classList.contains('form-error')) {
				element.parentElement.querySelector('.form-error').remove();
			};
			element.classList.remove('form-error-field');
		},

		eventActivation: function () {
			this.fieldValidationEvent(this.arrayInputs);
		},

		// Вешаем событие для проверки пустоты input'ов
		fieldValidationEvent: function (arrayField) {
			let field;

			for (var index in arrayField) {
				debugger
				if (arrayField[index].TYPE == 'hidden') continue;
				field = document.querySelector("[name='" + arrayField[index].DATA_NAME + "']");
				BX.bind(field, 'input', this.fieldEmpty);
			};
		},

		// Проверка input'a на пустой value
		fieldEmpty: function (field) {
			debugger
			if (event != undefined && event.target.constructor.name == 'HTMLInputElement') field = event.target;
			debugger
			if (field.value.length == '') {
				debugger
				field.parentNode.classList.remove('callback-form__field-not-empty');
			} else {
				debugger
				field.parentNode.classList.add('callback-form__field-not-empty');
			}
		},

		clearForm: function (arrayField) {
			let field;

			for (var index in arrayField) {
				if (arrayField[index].TYPE != "hidden") {
					field = document.querySelector("[name='" + arrayField[index].DATA_NAME + "']");
					field.value = '';
					BX.JCWebForm.fieldEmpty(field);
				}
			};
		},

		shoppingCartValidation: function () {
			debugger
			let answer, basketContainer = document.querySelector('.catalog-basket__products'),
				message = BX.message('FORM_EMPTY_CART');
			if (this.basket == false) {
				basketContainer.classList.add('catalog-basket__empty');
				answer = new BX.PopupWindow(
					'form-result',
					null,
					{
						content: '<div class="form-result__shell form-result__error">' +
							'<div class="form-result__information-block">' +
							'<h2 class="form-result__title">' + message + '</h2>' +
							'</div>' +
							'</div>',
						zIndex: 0,
						autoHide: true,
						offsetTop: 1,
						offsetLeft: 0,
						lightShadow: true,
						closeIcon: true,
						closeByEsc: true,
						overlay: { backgroundColor: 'black', opacity: '80' },
					}
				);
				answer.show();
				this.error = true;
			} else {
				if (basketContainer.classList.contains('catalog-basket__empty')) basketContainer.classList.remove('catalog-basket__empty');
			}
		}
	}
})();
