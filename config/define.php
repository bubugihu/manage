<?php
define('UNDEL', 0);
define('DEL_FLAG', 1);
define('ACTIVE', 1);
define('INACTIVE', 0);

define('LIMIT', 20);

define('BASE_URL', env('BASE_URL', "http://manage.local/"));

define("IS_WRITE_NOT_YET", 0);
define("IS_WRITE_DONE", 1);
define("IS_WRITE", [
    IS_WRITE_NOT_YET => "NOT YET",
    IS_WRITE_DONE => "DONE",
]);

define("IS_PAYMENT_WAITING", 0);
define("IS_PAYMENT_DONE", 1);
define("IS_PAYMENT", [
    IS_PAYMENT_WAITING => "WAITING",
    IS_PAYMENT_DONE => "DONE",
]);

define('DATE_FORMAT_PHP', 'MM/dd/yyyy');
define('DATE_FORMAT_PHP_FOR_VALIDATE', 'mdy');
define('TIME_FORMAT_PHP', 'HH:mm:ss');
define('DATE_FORMAT_JS', "mm/dd/yyyy");

define('JSON_ENCODE', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
define('DEFAULT_CURRENCY_CODE', "USD");
define('DEFAULT_CURRENCY_ID', 2);

define("CATEGORY_PK", 0);
define("CATEGORY_B", 1);
define("CATEGORY_BS", 2);
define("CATEGORY_BHH", 3);
define("CATEGORY_BTT", 4);
define("CATEGORY_BTP", 5);
define("CATEGORY_BTSN", 6);
define("CATEGORY_BTL", 7);
define("CATEGORY_BTN", 8);
define("CATEGORY_OTHERS", 9);
define("CATEGORY", [
    CATEGORY_PK => "PHỤ KIỆN",
    CATEGORY_B => "BÓNG",
    CATEGORY_BS => "BÓNG SỐ",
    CATEGORY_BHH => "BÓNG HOẠT HÌNH",
    CATEGORY_BTT => "BÓNG TRÒN TRƠN",
    CATEGORY_BTP => "BÓNG TRÒN PASTEL",
    CATEGORY_BTSN => "BÓNG TRONG SIÊU NHŨ CHROME",
    CATEGORY_BTL => "BÓNG TRÒN LÌ RETRO",
    CATEGORY_BTN => "BÓNG TRÒN NHŨ",
    CATEGORY_OTHERS => "KHÁC",
]);

define("UNIT_C", 0);
define("UNIT_V", 1);
define("UNIT_D", 2);
define("UNIT_B", 3);
define("UNIT_S", 4);
define("UNIT_O", 5);
define("UNIT", [
    UNIT_C => "CÁI",
    UNIT_V => "VỈ",
    UNIT_D => "DÂY",
    UNIT_B => "BỘ",
    UNIT_S => "SET",
    UNIT_O => "KHÁC",
]);

define("STATUS_NEW", 0);
define("STATUS_PROCESS", 1);
define("STATUS_DONE", 2);
define("STATUS_CANCEL", 3);
define("STATUS", [
    STATUS_NEW => "NEW",
    STATUS_PROCESS => "PROCESS",
    STATUS_DONE => "DONE",
    STATUS_CANCEL => "CANCEL",
]);

define("SOURCE_SHOPEE", 0);
define("SOURCE_ZALO", 1);
define("SOURCE_OTHERS", 2);
define("SOURCE", [
    SOURCE_SHOPEE => "SHOPEE",
    SOURCE_ZALO => "ZALO",
    SOURCE_OTHERS => "OTHERS",
]);
define("STATUS_QUOTING_NEW", 0);
define("STATUS_QUOTING_PROCESS", 1);
define("STATUS_QUOTING_DONE", 2);
define("STATUS_QUOTING_CANCEL", 3);
define("STATUS_QUOTING_RETURN", 4);
define("STATUS_QUOTING", [
    STATUS_QUOTING_NEW => "Tạo mới",
    STATUS_QUOTING_PROCESS => "Đang giao",
    STATUS_QUOTING_DONE => "Hoàn thành",
    STATUS_QUOTING_CANCEL => "Đã hủy",
    STATUS_QUOTING_RETURN => "Hoàn trả",
]);
