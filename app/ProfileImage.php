<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    public $filename = '';
    private $image = '';

    public function __construct($image)
    {
        $this->image = $image;
        $this->filename = $this->getFileName();
    }

    private function getFileName()
    {
        $filename = hash('sha256',$this->image->getFilename().$this->image->getATime()) . '.' . $this->image->extension();
        return $filename;
    }
}
