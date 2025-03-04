<?php
class DataEncryption {
    private $key;
    private $method = 'aes-256-cbc';
    
    public function __construct($key = null) {
        if ($key === null) {
            // Load from secure config or environment
            $this->key = base64_decode(getenv('ENCRYPTION_KEY'));
        } else {
            $this->key = $key;
        }
    }
    
    public function encrypt($data) {
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        $encrypted = openssl_encrypt(
            $data,
            $this->method,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        // Return IV + encrypted data
        return base64_encode($iv . $encrypted);
    }
    
    public function decrypt($data) {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        
        return openssl_decrypt(
            $encrypted,
            $this->method,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
    
    // Generate a new encryption key
    public static function generateKey() {
        return base64_encode(openssl_random_pseudo_bytes(32));
    }
}

// Usage example:
// $encryption = new DataEncryption();
// $encryptedData = $encryption->encrypt("Sensitive data");
// Store $encryptedData in database
// Later: $decryptedData = $encryption->decrypt($encryptedData);
?>
