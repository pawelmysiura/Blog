<?php
namespace AppBundle\Service;

class TokenGenerator
{
    public function generateActiveToken()
    {
        $random = random_bytes(30);

        return base64_encode(bin2hex($random));
    }
}