<?php

class Validator {

    // Валидация работает только для одного правила (6.11.24) пример ([], ["email"=> "required"])
    public static function validate($data, $rules)
    {
        foreach ($rules as $key => $value) {
            switch ($value) {
                case 'required':
                    $validate = self::isRequired($data[$key] ?? "");

                    if(!$validate) {
                        return [
                            "success" => false,
                            "key" => $key,
                            "value" => $data[$key] ?? ""
                        ];
                    }
                break;
            }
        }

        return [
            "success" => true,
        ];
    }

    private static function isRequired($value)
    {
        if(!isset($value) || isset($value) && gettype($value) == "string" && strlen($value) === 0) {
            return false;
        }
        return true;
    }

    private static function isPhone()
    {

    }

    private static function isEmail()
    {

    }

}