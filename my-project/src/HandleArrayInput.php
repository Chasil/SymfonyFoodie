<?php

namespace App;

trait HandleArrayInput
{
    /**
     * @param string $data
     * @return string[]
     */
    public function transformStringToArray(string $data): array
    {
        return array_map(
            'trim',
            explode(",", $data)
        );
    }

    public function transformArrayToString(array $data): string
    {
        return implode(",", $data);
    }
}