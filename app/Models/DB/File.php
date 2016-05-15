<?php

namespace App\Models\DB;
use Illuminate\Database\Eloquent\Model;


class File extends Model{

    protected $fillable = ["project_id","title","file"];

    public function getUrlAttribute(){
        return route('file.get',['path' => $this->file]);
    }
}