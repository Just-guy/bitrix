<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!CModule::IncludeModule("iblock"))
	return;

$ext = 'wmv,wma,flv,vp6,mp3,mp4,aac,jpg,jpeg,gif,png';

$arTemplateParameters = array(
	"DISPLAY_TITLE_ELEMENT" => [
		"PARENT" => "DATA_SOURCE",
		"NAME" => GetMessage("ZD_DISPLAY_TITLE_ELEMENT"),
		"TYPE" => "STRING",
		"DEFAULT" => ""
	],
	"SELECT_FILE" => array(
		"PARENT" => "DATA_SOURCE",
		"NAME" => GetMessage("ZD_SELECT_FILE"),
		"TYPE" => "FILE",
		"FD_TARGET" => "F",
		"FD_EXT" => $ext,
		"FD_UPLOAD" => true,
		"FD_USE_MEDIALIB" => true,
		"FD_MEDIALIB_TYPES" => array('video', 'sound')
	)
);
