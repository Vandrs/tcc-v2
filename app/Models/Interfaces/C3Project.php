<?php

namespace App\Models\Interfaces;

Interface C3Project
{
    public function imageCoverOrFirst();
    public function getMembers();
    public function getFollowers();
    public function getPosts();
}
