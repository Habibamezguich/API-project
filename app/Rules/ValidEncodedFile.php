<?php

namespace App\Rules;

use App\Http\Misc\Helpers\Base64Handler;
use Illuminate\Contracts\Validation\Rule;

class ValidEncodedFile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($extensions = [])
    {
        $this->extensions = $extensions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $extension = Base64Handler::getExtension($value);
        return in_array($extension, $this->extensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid File.';
    }
}
