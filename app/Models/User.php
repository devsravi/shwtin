<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property-read string|null $access_token
 * @property-read string|null $account_id
 * @property-read bool $admin
 * @property-read array|null $approved_scopes
 * @property-read string|null $avatar
 * @property-read string|null $avatar_original
 * @property-read CarbonInterface $created_at
 * @property-read string|null $data
 * @property-read CarbonInterface $deleted_at
 * @property-read string $email
 * @property-read CarbonInterface|null $email_verified_at
 * @property-read CarbonInterface|null $expires_at
 * @property-read int|null $expires_in
 * @property-read string|null $first_name
 * @property-read int $id
 * @property-read string|null $last_name
 * @property-read string $name
 * @property-read string $password
 * @property-read string|null $phone_number
 * @property-read string|null $provider
 * @property-read string|null $refresh_token
 * @property-read string|null $remember_token
 * @property-read bool|null $send_updates
 * @property-read string|null $token_type
 * @property-read bool $tnc
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $two_factor_confirmed_at
 * @property-read string|null $two_factor_secret
 * @property-read string|null $two_factor_recovery_codes
 */
final class User extends Authenticatable implements MustVerifyEmail
{
    /**
     * @use HasFactory<\Database\Factories\UserFactory>
     * @use Notifiable<\Illuminate\Notifications\Notifiable>
     * @use softDeletes<\Illuminate\Database\Eloquent\SoftDeletes>
     * @use TwoFactorAuthenticatable<\Laravel\Fortify\TwoFactorAuthenticatable>
     *
     * */
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'access_token',
        'account_id',
        'admin',
        'approved_scopes',
        'avatar',
        'avatar_original',
        'data',
        'email',
        'email_verified_at',
        'expires_at',
        'expires_in',
        'first_name',
        'last_name',
        'name',
        'password',
        'phone_number',
        'provider',
        'refresh_token',
        'send_updates',
        'token_type',
        'tnc',
        'two_factor_confirmed_at',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'access_token',
        'password',
        'remember_token',
        'refresh_token',
        'two_factor_recovery_codes',
        'two_factor_secret',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'admin' => 'boolean',
            'approved_scopes' => 'array',
            'email_verified_at' => 'datetime',
            'expires_at' => 'datetime',
            'expires_in' => 'integer',
            'password' => 'hashed',
            'send_updates' => 'boolean',
            'tnc' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * The function is return true if the record uer is admin
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }
}
