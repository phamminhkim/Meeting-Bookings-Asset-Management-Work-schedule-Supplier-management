<?php

namespace App\Notifications;

use Illuminate\Support\Str;

class NotiBaseModel {
    public $icon;
    public $title;
    public $content;
    public $content_full;
    public $date;
    public $url;
    public $title_prefix = '[eRequest] ';
    public $UID;

    public $object_type;
    public $object_id;

    public function __construct()
    {
        $this->UID = Str::uuid();
    }

    public function getUID()
    {
        return strval($this->UID);
    }

    public function getFullTitle() {
        return $this->title_prefix.$this->title;
    }
}
