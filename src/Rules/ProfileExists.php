<?php

namespace Heloufir\SecurityStarter\Rules;

use Heloufir\SecurityStarter\Models\Profile;
use Illuminate\Contracts\Validation\Rule;

class ProfileExists implements Rule
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Profile::where('id', $this->id)->count() != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exists', ['attribute' => 'profile']);
    }
}
