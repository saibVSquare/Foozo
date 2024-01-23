<?php

namespace App\Http\Dtos;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;


class UserRegisterDto extends DataTransferObject
{
    public int $id = 0;
    public string $type = 'customer';
    public ?string $first_name = null;
    public ?string $last_name = null;
    public string $email;
    public ?string $phone = null;
    public ?string $password = null;
    public bool $is_active = true;
    public bool $is_verified = false;
    public string $newsletter_frequency = 'never';
    public string $lunch_reminder_frequency = 'never';
    public ?int $verification_code = null;
    public ?string $email_verified_at = null;
    public ?string $google_id = null;
    public ?string $facebook_id = null;


    public function __construct($args)
    {
        parent::__construct($args);
    }


    public static function fromRequest(Request $params): self
    {
        $self = collect([
            'id' => $params->input('user_id') ?? 0,
            'first_name' => $params->input('first_name'),
            'last_name' => $params->input('last_name'),
            'email' => $params->input('email'),
            'password' => bcrypt($params->filled('password')) ? bcrypt($params->filled('password')) : null,
            // 'is_active' => $params->filled('is_active') ? $params->input('is_active') : true,
            // 'is_verified' => $params->filled('is_verified') ? $params->input('is_verified') : false,
            // 'google_id' => $params->filled('google_id') ? $params->input('google_id') : null,
            // 'facebook_id' => $params->filled('facebook_id') ? $params->input('facebook_id') : null,
            // 'country_code' => $params->input('country_code'),
            // 'country_name' => $params->input('country_name')
        ]);


        if ($params->input('type', 'customer') == 'rider') {
            $self = $self->merge([
                'type' => 'rider'
            ]);
        }

        if ($params->filled('google_id') || $params->filled('facebook_id')) {
            if ($params->input('user_id') == 0) {
                $self = $self->replace(['is_verified' => true]);
            }
        }

        return new self($self->toArray());
    }
}
