<?php

namespace App\Services\Validation;

/**
 *
 */
class Validator
{
	private $errors;
	private $rules = [];

	public function __construct()
	{
		$this->errors = collect([]);
	}

	public function addRule($rule)
    {
        $this->rules[] = $rule;
    }

    public function addRules($rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    public function validate()
    {
        foreach ($this->rules as $rule) {
            $result = $rule->validate();
            if (!empty($result)) {
                $this->errors->push($result);
            }
        }

        return $this->errors;
    }

    public function valid()
    {
        return $this->validate()->isEmpty();
    }

    public function errors()
    {
        return $this->errors->map(function ($error) {
            return (object) [
                'message' => $error->getMessage(),
                'code' => $error->getCode()
            ];
        });
    }
}
