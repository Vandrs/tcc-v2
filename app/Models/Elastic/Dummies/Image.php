<?php

namespace App\Models\Elastic\Dummies;
use App\Utils\Model;
use App\Utils\ImageFileServerTrait;


class Image extends Model {
    use ImageFileServerTrait;
    protected $fillable = ["id", "title", "file", "cover"];
}