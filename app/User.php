<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Storage;

class User extends Authenticatable
{
    use Notifiable;

    const IS_BANNED = 1;
    const IS_ACTIVE = 0;


    protected $fillable = [
        'name', 'email'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);;
        $user->save();

        return $user;
    }

    public function generatePassword($password)
    {
        if ($password != null){
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function edit($fields)
    {
        $this->fill($fields);
        if ($fields['password'] != null){
            $this->password = bcrypt($fields['password']);
        }
        $this->save();
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if ($image == null){return; }

        $this->removeAvatar();

        $fileName = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $fileName);
        $this->avatar = $fileName;
        $this->save();
    }

    public function removeAvatar()
    {
        if ($this->avatar != null){
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getAvatar()
    {
        if ($this->avatar == null){
            return '/img/no-user-image.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin()
    {
        $this->is_admin = 1;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_admin = 0;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        return $value == null ? $this->makeNormal() : $this->makeAdmin();
    }

    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

    public function unBan()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        return $value == null ? $this->unBan() : $this->ban();
    }

}
