<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed status
 */
class Comment extends Model
{
    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    public function disAllow()
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus()
    {
        return $this->status == 0 ? $this->allow() : $this->disAllow();
    }

    public function remove()
    {
        $this->delete();
    }
}
