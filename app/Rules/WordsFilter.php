<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FiterWord implements ValidationRule
{

protected $words;


    public function __construct($words)
    {
        $this->words=$words;
       
    }

   
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($this->words as $word){
            if(stripos($value,$word) !==false){
                $fail("الحقل $attribute يحتوي على كلمة ممنوعة: {$word}");
          
            }

        }
    }
}
