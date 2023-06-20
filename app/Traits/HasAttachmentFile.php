<?php
namespace App\Traits;

use App\Models\shared\File;
use App\Models\shared\Image;

trait HasAttachmentFile{
    public function attachment_file(){
        return $this->morphMany(File::class,'fileable');
    }
    public function attachment_image(){
        return $this->morphMany(Image::class,'imgable');
    }

}


?>
