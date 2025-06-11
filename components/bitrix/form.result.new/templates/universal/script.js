BX.namespace('BX.JCWebForm');

(function () {
	'use strict';

	BX.JCWebForm = {
		init: function (parameters) {
			this.result = parameters.result;
			if (this.result.FORM_ID.length != '') this.form = document.querySelector('#' + this.result.FORM_ID + ' form');
			this.openFormInModalWindow = this.result.OPEN_FORM_IN_MODAL_WINDOW;
			this.formCallButton = document.querySelector('.' + this.result.CLASS_FORM_CALL_BUTTON);
			this.captchaCodeImg = document.querySelector('.universal-form__captcha-code');
			this.captchaCodeInput = document.querySelector('[name="captcha_sid"]');
			this.pathToAjaxComponent = this.result.PATH_TO_AJAX_COMPONENT;
			this.pathToAjaxResult = this.result.PATH_TO_AJAX_RESULT;
			this.callingForm = this.result.CALLING_FORM;
			this.submitEventName = this.result.SUBMIT_EVENT_NAME;
			this.agreementVerified = (this.result.USER_CONSENT == 'Y' ? true : false);
			this.lastResult = null;

			if (this.form != null) {
				this.webFormId = this.form.querySelector('[name="WEB_FORM_ID"]').value;
				this.sendFormButton = this.form.querySelector('[type="submit"]');
				this.phoneField = this.form.querySelector('[data-validation="telephone"]');
				this.useCaptcha = this.result.USE_CAPTCHA;
				this.error = false;
				this.arrayInputs = this.result.INPUTS;
				this.eventActivation();
				this.fieldValidationEvent(this.arrayInputs);
				this.clearForm(this.arrayInputs);
			}

			if (this.useCaptcha && this.form != null) {
				this.initializeCaptcha();
			}

			if (this.phoneField != null) this.setMaskForPhone(this.phoneField);

			if (this.formCallButton != null && this.openFormInModalWindow == "Y" && this.callingForm.length == '') BX.bind(this.formCallButton, 'click', BX.proxy(this.callForm, this));

			if (this.sendFormButton != null) {
				BX.bind(this.form, 'submit', BX.proxy(this.sendForm, this));

				BX.bind(this.sendFormButton, 'click', function () {
					BX.onCustomEvent(this.submitEventName, []);
				});
			}
		},

		callForm: function () {
			let promise = BX.ajax.promise({
				url: this.pathToAjaxComponent,
				method: 'POST',
				dataType: 'html',
				data: {
					params: this.result.PARAMETERS,
					componentName: this.result.COMPONENT_NAME,
					templateName: this.result.TEMPLATE_NAME,
					calling_form: true,
					useCaptcha: this.useCaptcha
				}
			});

			promise
				.then(function (response) {
					let parseHtmlShell, parseHtmlElement, oPopup;
					parseHtmlShell = $.parseHTML(response);

					oPopup = new BX.PopupWindow('form-universal-' + Math.random().toString(16).slice(2), null, {
						content: parseHtmlShell[1],
						autoHide: true,
						offsetTop: 1,
						offsetLeft: 0,
						lightShadow: true,
						closeIcon: true,
						closeByEsc: true,
						className: 'popup-form-universal',
						events: {
							onAfterPopupShow: function () {
								oPopup.adjustPosition();
								oPopup.resizeOverlay();
							}
						},
						overlay: {
							backgroundColor: 'black', opacity: '80'
						},
					});
					oPopup.show();
				}.bind(this))
				.catch(function () {
					//lazyPortionAjaxCall.ajaxOnAir = false; // (?)
				});
		},

		sendForm: function (event) {
			let fieldValues;
			this.error = false;
			fieldValues = this.formFieldValues(this.arrayInputs);

			BX.PreventDefault(event);
			if (this.error == true && this.agreementVerified == false) return false;

			BX.ajax.submitAjax(this.form, {
				url: this.pathToAjaxResult,
				method: 'POST',
				dataType: 'json',
				data: {
					fieldValues: fieldValues,
					webFormId: this.webFormId,
					arrayInputs: this.arrayInputs
				},
				onsuccess: function (data, response) {

					this.clearMessage();
					this.refreshCaptcha(data.CAPTCHA_CODE);
					if (this.lastResult != null) this.form.closest('.universal-form').classList.remove(this.lastResult);

					this.lastResult = 'universal-form__result_' + data.RESULT;
					this.form.closest('.universal-form').classList.add(this.lastResult);
					this.showMessage(data.MESSAGE);
					this.clearForm(data.ARRAY_INPUTS);
				}.bind(this),
				onfailure: function (data, response) {
					this.clearMessage();
					this.form.closest('.universal-form').classList.add('universal-form__result_false');
					this.showMessage('FORM_TITLE_FALSE')
					this.clearForm(data.ARRAY_INPUTS);
				}.bind(this)
			});
		},

		setMaskForPhone: function (field) {
			IMask(field, {
				mask: '+{7} (000) 000-00-00'
			});
		},

		initializeCaptcha: function () {
			try {
				Recaptchafree.reset();
			} catch (err) {

			}
		},

		formFieldValues: function (arrayField) {
			let field, resultValue = [];

			for (var index in arrayField) {
				switch (arrayField[index].TYPE) {
					case 'hidden':
						field = this.hiddenFieldValue(arrayField[index], index);
						break;

					case 'radio':
						field = this.radioButtonFieldValue(arrayField[index]);
						break;

					default:
						field = this.textFieldValue(arrayField[index]);
						break;
				}

				if ((arrayField[index].TYPE != 'hidden' || arrayField[index].TYPE != 'radio') && arrayField[index].REQUIRED == "Y") this.fieldValidation(field);

				resultValue[field.name] = field.value;
				field = '';
			};

			return resultValue;
		},

		textFieldValue: function (fieldData) {
			let field = this.form.querySelector("[name='" + fieldData.DATA_NAME + "']");
			return field;
		},

		radioButtonFieldValue: function (fieldData) {
			let field, checkedButton;
			for (var index in fieldData.ID) {
				field = this.form.querySelector("[id='" + fieldData.ID[index] + "'][name='" + fieldData.DATA_NAME + "']")

				if (field.checked) checkedButton = field;
			}

			return (checkedButton instanceof Element ? checkedButton : '');
		},

		hiddenFieldValue: function (fieldData, code) {
			let field = this.form.querySelector("[name='" + fieldData.DATA_NAME + "']");

			if (code == 'PRODUCT') {
				field.value = this.result.DATA.NAME + ' - ' + this.result.DATA.PRICE + ' - ' + this.form.querySelector('.product-card-count__num').value + 'шт' + "\r\n" + "Ссылка на товар: " + window.location.protocol + '//' + window.location.hostname + this.result.DATA.SRC_TO_PRODUCT;
			}

			return field;
		},

		fieldValidation: function (field) {
			let message, validation, type, pattern;

			type = field.dataset.validation;
			if (type == 'telephone') {
				message = 'FORM_REQUIRED_TELEPHONE';
				pattern = new RegExp("^((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10}$", "gmi");
			}

			if (type == 'email') {
				message = 'FORM_REQUIRED_EMAIL';
				pattern = new RegExp("^[A-Z0-9._%+-]+@[A-Z0-9-]+\.[A-Z]{2,4}$", "gmi");
			}

			if (pattern != undefined && pattern.length != 0) validation = pattern.test(field.value);

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

			element.before(BX.create('SPAN', { props: { className: 'form-error' }, text: BX.message(message) }));
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
			if (BX.UserConsent) {
				let control = BX.UserConsent.load(this.form);
				if (!control) {
					return;
				}

				BX.addCustomEvent(
					control,
					BX.UserConsent.events.save,
					BX.proxy(function (data) {
						this.agreementVerified = false;
					}, this)
				);

				BX.addCustomEvent(
					control,
					BX.UserConsent.events.refused,
					BX.proxy(function (data) {
						this.agreementVerified = true;
					}, this)
				);
			}
		},

		// Вешаем событие для проверки пустоты input'ов
		fieldValidationEvent: function (arrayField) {
			let field;

			for (var index in arrayField) {
				if (arrayField[index].TYPE == 'hidden') continue;
				field = document.querySelector("[name='" + arrayField[index].DATA_NAME + "']");
				BX.bind(field, 'input', this.fieldEmpty);
			};
		},

		// Проверка input'a на пустой value
		fieldEmpty: function (field) {
			if (event != undefined && event.target.constructor.name == 'HTMLInputElement') field = event.target;

			if (field.value.length == '') {
				field.parentNode.classList.remove('universal-form__field-not-empty');
			} else {
				field.parentNode.classList.add('universal-form__field-not-empty');
			}
		},

		clearForm: function (arrayField) {
			let field;
			for (var index in arrayField) {
				if (arrayField[index].TYPE == 'hidden' || arrayField[index].TYPE == 'radio') continue;

				field = document.querySelector("[name='" + arrayField[index].DATA_NAME + "']");
				field.value = '';
			};
		},

		showMessage: function (messageCode) {
			this.form.before(
				BX.create(
					'DIV',
					{
						props: {
							className: 'universal-form__message'
						},
						text: BX.message(messageCode)
					}
				)
			);
		},

		clearMessage: function () {
			let message = this.form.previousElementSibling;
			if(message != null) message.remove();
		},

		refreshCaptcha: function (captchaCode) {
			this.captchaCodeImg.src = "/bitrix/tools/captcha.php?captcha_sid=" + captchaCode;
			this.captchaCodeInput.value = captchaCode;
		},

		resultConsentProcessingPersonalData: function () {
			let input;
			input = document.querySelector('.universal-form__agreement input');
			return input.checked;
		}
	}
})();
