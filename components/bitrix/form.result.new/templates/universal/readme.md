# Вызов компонента
```
$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"universal", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"DESCRIPTION" => "",
		"EDIT_URL" => "result_edit.php",
		"SUCCESSFUL_RESULT_SEPARATE_WINDOW" => "Y",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "result_list.php",
		"NAME_FORM_CALL_BUTTON" => "",
		"OPEN_FORM_IN_MODAL_WINDOW" => "N",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"TITLE" => "",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "1",
		"COMPONENT_TEMPLATE" => "universal",
		"TITLE_SUCCESSFUL_RESULT" => "Спасибо!",
		"DESCRIPTION_SUCCESSFUL_RESULT" => "В ближайшее время с вами свяжется администратор для уточнения деталей",
		"TITLE_FAILURE_RESULT" => "Ошибка",
		"DESCRIPTION_FAILURE_RESULT" => "Попробуйте ещё раз",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);
```

# Дополнение
Для валидации поля с номером телефона в настройках самого поля найти поле «Параметры»:
<img width="1422" height="320" alt="image" src="https://github.com/user-attachments/assets/e64ee36b-5715-4b92-9015-17539aaf8241" />

И добавить строку:
```html
data-validation="telephone"
```
