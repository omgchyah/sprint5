<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'role',
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function getSuccessPercentage(): float
    {
        $totalGames = $this->games()->count();
        $wonGames = $this->games()->where('result', 'W')->count();

        return ($totalGames > 0) ? ($wonGames / $totalGames * 100) : 0;

    }

    public static function getSuccessAveragePercentage()
    {
        $users = self::whereIn('role', ['user', 'guest'])->with('games')->get();

        $totalSuccessPercentage = 0;
        $numberPlayers = $users->count();

        foreach($users as $user) {
            $totalSuccessPercentage += $user->getSuccessPercentage();
        }

        return ($numberPlayers > 0) ? ($totalSuccessPercentage / $numberPlayers) : 0;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
