function lazyPortionInit() {
	"use strict";

	let ajaxElement = document.querySelector('span[data-ajax-src]');

	ajaxElement.addEventListener('click', lazyPortionAjaxCall)
}

document.addEventListener('DOMContentLoaded', lazyPortionInit)

function lazyPortionAjaxCall() {

	if (lazyPortionAjaxCall.ajaxOnAir) {
		return
	}

	let pagenavTotal = +this.getAttribute('data-pagenav-total'),
		pagenavCurrent = +this.getAttribute('data-pagenav-current'),
		ajaxUrl = this.getAttribute('data-ajax-src'),
		ajaxLoader = document.querySelector('.reviews-list__loader');

	if (pagenavCurrent >= pagenavTotal) {
		this.removeEventListener('click', lazyPortionAjaxCall);
		return;
	}

	$(ajaxLoader).css({ 'display': 'block' });

	lazyPortionAjaxCall.ajaxOnAir = true;

	let = ajaxUrlParams = {},
		ajaxUrlParams['PAGEN_1'] = pagenavCurrent + 1;

	let promise = BX.ajax.promise({
		//url: this.getAttribute('data-ajax-src'),
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
		.then(function (response) {
			let pagenavTotal = +this.getAttribute('data-pagenav-total'),
				pagenavCurrent = +this.getAttribute('data-pagenav-current'),
				ajaxLoader = document.querySelector('.reviews-list__loader');

			let $domElem = document.querySelector('.reviews-list__shell'),
				parseHtmlShell = $.parseHTML(response),
				parseHtmlElement = $.parseHTML(parseHtmlShell[0].children[0].innerHTML)
			$.each(parseHtmlElement, function (key, value) {
				if (parseHtmlElement[key].nodeName == 'DIV') {
					$domElem.append(value);
				}
			})
			$(ajaxLoader).css({ 'display': 'none' });
			lazyPortionAjaxCall.ajaxOnAir = false;
			this.setAttribute('data-pagenav-current', pagenavCurrent + 1);
			if ((pagenavCurrent + 1) >= pagenavTotal) {
				let parentClass = $(this).parent()[0].classList[0];
				$('.' + parentClass).addClass('reviews__lazy-load_disabled');
			}
		}.bind(this))
		.catch(function () {
			lazyPortionAjaxCall.ajaxOnAir = false;
		})

}