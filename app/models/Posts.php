<?php
use Phalcon\Mvc\Model;

class Posts extends Model
{
    public function getPosts()
    {
        return "posts";
    }
}