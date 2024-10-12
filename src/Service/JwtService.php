<?php 


namespace App\Service;



class JwtService 
{

    /**
     * generation jeton
     *
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param integer $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        // creation ecart date debut, date fin
        if($validity > 0){
            $now = new \DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

 

        // encodage base 64
        $base64Header = base64_encode(json_encode($header));
        $base64PayLoad = base64_encode(json_encode($payload));
        // nettoyage
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header );
        $base64PayLoad = str_replace(['+', '/', '='], ['-', '_', ''], $base64PayLoad );
        // generer signature
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256',$base64Header . '.' . $base64PayLoad, $secret, true);
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);
        // token creation
        $jwt = $base64Header . '.' . $base64PayLoad . '.' . $base64Signature;
        return $jwt;
    }

    /**
     * validitation jeton
     *
     * @param string $token
     * @return boolean
     */
    public function isValid(string $token): bool
    {
        return preg_match('/^[a-zA-Z0-9\-\_\ ]+\.[a-zA-Z0-9\-\_\ ]+\.[a-zA-Z0-9\-\_\ ]+$/', $token)===1;
    }

    /**
     * getter header
     *
     * @param string $token
     * @return array
     */
    public function getHeader(string $token): array
    {
        // decoupe token
        $array = explode('.', $token);
        // decodage token_header
        $header = json_decode(base64_decode($array[0]),true);

        return $header;
    }

    /**
     * getter payload
     *
     * @param string $token
     * @return array
     */
    public function getPayload(string $token): array
    {
        // decoupe token
        $array = explode('.',$token);
        // decodage token_payload
        $payload = json_decode(base64_decode($array[1]),true);

        return $payload;
    }

    /**
     * controle de ma validité
     *
     * @param string $token
     * @return boolean
     */
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new \DateTimeImmutable();
        return $payload['exp'] < $now->getTimestamp();
    }

    public function check(string $token, string $secret): bool
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        $verifToken = $this->generate($header, $payload, $secret, 0);
        return $token === $verifToken;
    }


}