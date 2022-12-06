function lazyPortionInit() {
	"use strict"; // Про строгий режим https://learn.javascript.ru/strict-mode

	let ajaxElement = document.querySelector('span[data-ajax-src]'); // Возвращаем ссылку на объект

	ajaxElement.addEventListener('click', lazyPortionAjaxCall) // Прослушиваем событие click на элементе и по клику инициализируем функцию lazyPortionAjaxCall
}

document.addEventListener('DOMContentLoaded', lazyPortionInit) // Прослушиваем событие загрузки всего HTML
function lazyPortionAjaxCall() {

	// (?)
	//if (lazyPortionAjaxCall.ajaxOnAir) {
	//	return
	//}

	let pagenavTotal = +this.getAttribute('data-pagenav-total'),    // Возвращаем общее количество страниц и переводим в число
		pagenavCurrent = +this.getAttribute('data-pagenav-current'),  // Возвращаем текущую страницу и переводим в число
		ajaxUrl = this.getAttribute('data-ajax-src'),                 // Возвращаем путь к обрабатываемому ajax'ом файлу
		ajaxNavNum = this.getAttribute('data-pagenav-num'),           // Возвращаем номер постраничной навигации, который прикреплен к нашему компоненту
		ajaxLoader = document.querySelector('.reviews-list__loader'), // Возвращаем ссылку на объект, DOM элемент с заглушкой
		ajaxUrlParams = {};

	ajaxUrlParams['PAGEN_' + ajaxNavNum] = pagenavCurrent + 1;      // Формируем url параметр для следующей страницы

	// Если мы на последней странице, то удаляем прослушку события и завершаем скрипт
	if (pagenavCurrent >= pagenavTotal) {
		this.removeEventListener('click', lazyPortionAjaxCall);
		return;
	}

	// Показываем заглушку
	$(ajaxLoader).css({ 'display': 'block' });

	// (?)
	lazyPortionAjaxCall.ajaxOnAir = true;

	// Создаем ajax запрос и передаем параметры, навзвание компонента и название шаблона компонента. Оборачиваем его в промис
	let promise = BX.ajax.promise({
		url: BX.util.add_url_param(ajaxUrl, ajaxUrlParams),
		method: 'POST',
		dataType: 'html',
		data: {
			params: this.getAttribute('data-params'),
			componentName: this.getAttribute('data-component-name'),
			templateName: this.getAttribute('data-template-name'),
			ajax_call: true,
		}
	})


	promise
		.then(function (response) {                                                    // В response содержатся наши DOM элементы в тексте для следующей страницы
			let pagenavTotal = +this.getAttribute('data-pagenav-total'),                 // Возвращаем общее количество страниц и переводим в число
				pagenavCurrent = +this.getAttribute('data-pagenav-current'),             // Возвращаем текущую страницу и переводим в число
				ajaxLoader = document.querySelector('.reviews-list__loader'),            // Возвращаем ссылку на объект, DOM элемент с заглушкой
				domElem = document.querySelector('.reviews-list__shell'),                // Возвращаем ссылку на объект, DOM элемент оболочка
				parseHtmlShell = $.parseHTML(response),                                  // Конвертируем текст в HTML, возвращаем HTMLDivElement .reviews-list
				parseHtmlElement = $.parseHTML(parseHtmlShell[0].children[0].innerHTML); // Конвертируем текст в HTML, возвращаем HTMLDivElement .reviews-list__block

			$.each(parseHtmlElement, function (key, value) { // Проводим полученные элементы через цикл
				if (parseHtmlElement[key].nodeName == 'DIV') { // Если DOM элемент является div'ом
					domElem.append(value);                       // То добавляем его в конец DOM элемента .reviews-list__shell
				}
			})

			$(ajaxLoader).css({ 'display': 'none' }); // Убираем заглушку

			//lazyPortionAjaxCall.ajaxOnAir = false; // (?)

			this.setAttribute('data-pagenav-current', pagenavCurrent + 1); // Меняем номер текущей страницы, прибавляем значение data-атрибута data-pagenav-current на +1

			if ((pagenavCurrent + 1) >= pagenavTotal) { // Проверяем, если мы на последней странице
				$(domElem).parent().addClass('reviews__list_disabled'); //ТО присваиваем родительскому элементу .reviews-list класс .reviews__list_disabled
			}
		}.bind(this)) // Метод bind фиксирует контекст this ссылка на метод
		.catch(function () {
			//lazyPortionAjaxCall.ajaxOnAir = false; // (?)
		})

}