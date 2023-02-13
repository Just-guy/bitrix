(function (window) {
	"use strict"; // Про строгий режим https://learn.javascript.ru/strict-mode

	if (window.JCQuiz)
		return;

	window.JCQuiz = function (params) {
		this.quizdata = params;
		this.answerList = this.quizdata.ANSWER;
		this.mainQuizDiv = document.querySelector('.test-element');
		this.questionElement = document.querySelector('.test-element__question-title');
		this.questionImage = document.querySelector('.test-element__question-image');
		this.submit = document.querySelector('.test-element__button');
		this.quizContainer = document.querySelector('.test-element__container');
		this.quizName = document.querySelector('[name="form_text_1"]');
		this.quizTelephone = document.querySelector('[name="form_text_2"]');
		this.quizForm = document.querySelector('.test-element__form');
		this.quizList = document.querySelector('.test-element__list');
		this.currentQuiz = 0;
		this.score = 0;
		this.answerArr = [];
		BX.ready(BX.delegate(this.init, this));
	}
	window.JCQuiz.prototype = {
		init: function () {
			this.phoneMask(this.quizTelephone);
			this.loadQuiz();
			BX.bind(this.submit, 'click', BX.delegate(this.addResultAjaxCall, this));
		},

		// Проверяем заполнены ли поля и валидируем их
		inputValidate: function () {
			let field = [],
				input = document.querySelectorAll("input[data-validate]");

			// Вырезаем нижние подчеркивания, таким образом определяем, что пользователь ввел телефон не полностью
			//cutQuizTelephone = quizTelephone.value.replace(/_/g, "")

			input.forEach((element) => {
				field.push('input[data-validate]');
				let value = element.value,
					line = element.closest('.test-element__input');
				for (var i = 0; i < field.length; i++) {
					if (!value) {
						line.classList.add('test-element__line-required');
						setTimeout(function () {
							line.classList.remove('test-element__line-required')
						}.bind(element), 2000);
					}
				}
			});
		},

		loadQuiz: function () {
			// Получаем текущие вопрос и ответы
			let currentQuizData = this.answerList[this.currentQuiz],
				answerElements;

			//Помещаем вопрос и ответы в DOM элементы
			this.questionElement.innerText = currentQuizData.QUESTION;

			this.quizList.replaceChildren();
			for (var key in currentQuizData.ANSWER) {
				this.quizList.appendChild(
					BX.create('LI', {
						children: [
							BX.create('INPUT', {
								attrs: {
									"id": key,
									"name": "answer",
									"type": "radio",
								},
								props: {
									className: 'quiz__answer'
								}
							}),
							BX.create('LABEL', {
								attrs: {
									"for": key,
									"id": key + "_text",
								},
								"text": currentQuizData.ANSWER[key]
							}),
						]
					})
				)
			}

			this.questionImage.src = currentQuizData.IMAGE;
			answerElements = this.getAnswerElements('quiz__answer');
			this.deselectAnswers(answerElements);
		},

		// Отменить выбор радиокнопки
		deselectAnswers: function (node) {
			node.forEach(answerEl => answerEl.checked = false)
		},

		// Получаем выбранный элемент
		getSelected: function (node) {
			let answer = {}
			// Помещаем в answer отмеченный ответ
			node.forEach(answerEl => {
				if (answerEl.checked) {
					answer.id = answerEl.id;
					answer.text = (this.currentQuiz + 1) + ": " + this.answerList[this.currentQuiz].ANSWER[answer.id];
				}
			});
			return answer;
		},

		phoneMask: function (phoneNumber) {
			new BX.MaskedInput({
				mask: '+7 999 999 99 99',
				input: phoneNumber,
				placeholder: '_',
			});
		},

		// Получить массив ответов
		getAnswerElements: function (classElement) {
			let answerElements = document.querySelectorAll('.' + classElement);
			return answerElements;
		},

		addResultAjaxCall: function () {
			let answer, answerElements, srcAjax;

			this.inputValidate();
			if (this.quizName.value.length == 0 || this.quizTelephone.value.length == 0) return

			if (BX.style(this.quizForm, "display") == "block" || BX.style(this.quizContainer, "display") == "none") {
				BX.style(this.quizForm, "display", "none");
				BX.style(this.quizContainer, "display", "block");
			}

			answerElements = this.getAnswerElements('quiz__answer');
			answer = this.getSelected(answerElements);
			this.answerArr[this.currentQuiz] = answer.text;

			if (Object.entries(answer).length !== 0) {
				//Если ответила на вопрос правильно, то количество очков увеличивается
				if (answer.id === this.answerList[this.currentQuiz].CORRECT) {
					this.score++;
				}

				// Если ответили на вопрос, то меняем нумерацию вопроса
				this.currentQuiz++;

				// Если ответили не на все вопросы, то загружаем следующий, иначе показываем пользователю его результат
				if (this.currentQuiz < this.answerList.length) {
					this.loadQuiz();
				} else {
					BX.delegate(this.submit, this);
					BX.ajax({
						url: this.submit.getAttribute('data-ajax-src'),
						method: 'POST',
						dataType: 'html',
						data: {
							answer: this.answerArr,
							finishResult: this.score + "/" + this.answerList.length,
							name: this.quizName.value,
							telephone: this.quizTelephone.value,
							params: this.submit.getAttribute('data-params'),
							componentName: this.submit.getAttribute('data-component-name'),
						}
					})
					this.mainQuizDiv.innerHTML = `<h2>Вы правильно ответили на ${this.score}/${this.answerList.length} вопросов</h2>`;
				}
			}
		},

		linkToQuiz: function () {
			
		}
	}

})(window)
