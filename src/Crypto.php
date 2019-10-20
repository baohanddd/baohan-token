<?php
namespace baohan\token;

use baohan\token\Crypto\Exception\DecryptFailure;
use baohan\token\Crypto\Exception\IncompleteCode;
use Phalcon\Di;

/**
 * 加密解密
 * Class Crypto
 * @package baohan\token
 */
class Crypto
{
    /**
     * 私钥
     * @var string
     */
    protected $pk = "i7p0TdjdOxAfl3mtrk9k99HCauZWyW4Y";

    public function __construct($pk = "")
    {
        if($pk) {
            $this->pk = $pk;
        }
    }

    /**
     * @return string
     */
    protected function getPrivateKey() : string
    {
        return $this->pk;
    }

    /**
     * @param $message
     * @return string
     * @throws \Exception
     */
    public function encrypt($message)
    {
        $secret_key = $this->getPrivateKey();

        $block_size = 64;

        // create a nonce for this operation. it will be stored and recovered in the message itself
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        // pad to $block_size byte chunks (enforce 512 byte limit)
        $padded_message = sodium_pad($message, $block_size);

        // encrypt message and combine with nonce
        $cipher = base64_encode($nonce . sodium_crypto_secretbox($padded_message, $nonce, $secret_key));

        // cleanup
        sodium_memzero($message);
        sodium_memzero($secret_key);

        return $cipher;
    }

    /**
     * @param $encrypted
     * @return string
     * @throws \Exception
     */
    public function decrypt($encrypted)
    {
        $secret_key = $this->getPrivateKey();

        $block_size = 64;

        // unpack base64 message
        $decoded = base64_decode($encrypted);

        // check for general failures
        if ($decoded === false) {
            throw new \Exception('The encoding failed');
        }

        // check for incomplete message. CRYPTO_SECRETBOX_MACBYTES doesn't seem to exist in this version...
        if (!defined('CRYPTO_SECRETBOX_MACBYTES')) define('CRYPTO_SECRETBOX_MACBYTES', 16);
        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + CRYPTO_SECRETBOX_MACBYTES)) {
            throw new IncompleteCode('The message was truncated');
        }

        // pull nonce and ciphertext out of unpacked message
        $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        // decrypt it and account for extra padding from $block_size (enforce 512 byte limit)
        $decrypted_padded_message = sodium_crypto_secretbox_open($ciphertext, $nonce, $secret_key);
        $message = sodium_unpad($decrypted_padded_message, $block_size);

        // check for encrpytion failures
        if ($message === false) {
            throw new DecryptFailure('The message was tampered with in transit');
        }

        // cleanup
        sodium_memzero($ciphertext);
        sodium_memzero($secret_key);

        return $message;
    }
}