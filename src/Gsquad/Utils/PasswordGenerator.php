<?php
/**
 * Created by PhpStorm.
 * User: florentingarnier
 * Date: 05/12/2016
 * Time: 16:08
 */

namespace Gsquad\Utils;


class PasswordGenerator
{
    public function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

}