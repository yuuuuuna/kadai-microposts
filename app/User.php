<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /** The attributes that should be hidden for arrays.
     *  @var array*/
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /** The attributes that should be hidden for arrays.
     *  @var array*/
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    
    public function microposts(){
        return $this->hasMany(Micropost::class);
    }
    
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    
    public function follow($userId)
    {
        // confirm if already following
        $exist = $this->is_following($userId);
        // confirming that it is not you
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // do nothing if already following
            return false;
        } else {
            // follow if not following
            $this->followings()->attach($userId);
            return true;
            }
    }

    public function unfollow($userId)
    {
        // confirming if already following
        $exist = $this->is_following($userId);
        // confirming that it is not you
        $its_me = $this->id == $userId;
    
    
        if ($exist && !$its_me) {
            // stop following if following
            $this->followings()->detach($userId);
            return true;
        } else {
            // do nothing if not following
            return false;
            }
    }


    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    
    
    //favorite親
    public function favoritings()
    {
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id' ,'micro_id')->withTimestamps();
    }
    
    /**
    public function like()
    {
        return $this->belongsToMany(User::class, 'user_favorite', 'micro_id', 'user_id')->withTimestamps();
    }
    */
    
    
    public function favorite($microId)
    {
        // confirm if already favorite
        $exist = $this->is_favorite($microId);
        
        if ($exist) {
            // do nothing if already favorite
            return false;
        } else {
            // follow if not following
            $this->favoritings()->attach($microId);
            return true;
        }
    }

    public function unfavorite($microId)
    {
        // confirming if already following
        $exist = $this->is_favorite($microId);
        
        if ($exist) {
            // stop following if following
            $this->favoritings()->detach($microId);
            return true;
        } else {
            // do nothing if not following
            return false;
        }
    }


    public function is_favorite($microId) {
        return $this->favoritings()->where('micro_id', $microId)->exists();
    }
    
    

}