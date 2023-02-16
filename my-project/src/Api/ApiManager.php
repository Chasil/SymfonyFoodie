<?php

namespace App\Api;

class ApiManager
{
    public function doesRecipieExist(array $recipies): bool
    {
        if (!$recipies['meals']) {
            return false;
        }
        return true;
    }
}