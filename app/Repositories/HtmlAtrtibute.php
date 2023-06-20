<?php

namespace App\Repositories;

class HtmlAtrtibute
{
    public $control_id = "";
    public $has_custom_label = false; //Nếu false thì sử dụng mặc định theo form, true: thì sử dụng giá trị $custom_label_text
    public $custom_label_text = "";
    public $has_default_value = false; //Nếu false thì sử dụng mặc định theo form, true: thì sử dụng giá trị $defaultValue
    public $default_value = "";

    public $require_validation = false;
    public $is_read_only = false;
    public $isDisabled = false;
    public $isVisible = true;

    public $type = "";
    public $items = [];
    public $value = "";
    public $subvalue = "";

    public $attachment_file = [];
    public $attachment_file_removed = [];
}
