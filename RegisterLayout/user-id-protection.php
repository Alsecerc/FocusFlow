<?php
class UserIdProtection {
    private static $key = null;
    
    private static function getEncryptionKey() {
        if (self::$key === null) {
            // Get from a secure location (environment variable or secure config)
            $keyFile = '/path/outside/webroot/app_keys.php';
            if (file_exists($keyFile)) {
                include $keyFile; // Should define $encryption_key
                self::$key = isset($encryption_key) ? $encryption_key : null;
            }
            
            // Fallback - not recommended for production
            if (self::$key === null) {
                self::$key = 'change-this-to-a-secure-key-stored-outside-webroot';
            }
        }
        return self::$key;
    }
    
    // Obfuscate user ID for external use (reversible)
    public static function obfuscateUserId($userId) {
        // Combine with a timestamp to prevent the same userId always producing the same output
        $timestamp = time();
        $data = $userId . '|' . $timestamp;
        
        // Simple XOR encoding with HMAC to verify integrity
        $key = self::getEncryptionKey();
        $hmac = hash_hmac('sha256', $data, $key, true);
        $obfuscated = base64_url_encode($data . $hmac);
        
        return $obfuscated;
    }
    
    // De-obfuscate user ID
    public static function deobfuscateUserId($obfuscated) {
        try {
            $key = self::getEncryptionKey();
            $decoded = base64_url_decode($obfuscated);
            
            // Extract data and HMAC
            $hmacLength = 32; // SHA-256 hash length
            $data = substr($decoded, 0, -$hmacLength);
            $hmac = substr($decoded, -$hmacLength);
            
            // Verify HMAC
            $expectedHmac = hash_hmac('sha256', $data, $key, true);
            if (!hash_equals($expectedHmac, $hmac)) {
                return false; // Tampering detected
            }
            
            // Extract user ID
            list($userId, $timestamp) = explode('|', $data);
            
            // Optional: Check if timestamp is within acceptable range
            if ((time() - $timestamp) > (30 * 86400)) {
                return false; // Expired (older than 30 days)
            }
            
            return $userId;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Helper functions for URL-safe base64
function base64_url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64_url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}
?>
