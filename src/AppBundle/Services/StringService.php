<?php

namespace AppBundle\Services;

class StringService
{
    public function generateToken($length)
    {
        return bin2hex(random_bytes($length / 2));
    }
}
