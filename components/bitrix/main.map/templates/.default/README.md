<h2>Выводим компонент</h2>

```php
$APPLICATION->IncludeComponent(
  "bitrix:main.map",
  ".default",
  array(
    "CACHE_TIME" => "3600000",
    "CACHE_TYPE" => "A",
    "COL_NUM" => "1",
    "LEVEL" => "4",
    "SET_TITLE" => "Y",
    "SHOW_DESCRIPTION" => "N",
    "COMPONENT_TEMPLATE" => ".default",
    "IBLOCK_TYPE" => "medcenter",
    "IBLOCK_ID" => "15"
  ),
  false
);
```
