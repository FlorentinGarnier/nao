<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 13/11/2016
 * Time: 10:33
 */

namespace Gsquad\BlogBundle\Utils;


class Slugger
{
    public function slugify($string)
    {
        return preg_replace(
            '/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string)))
        );
    }
}