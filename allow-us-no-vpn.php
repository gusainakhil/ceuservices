<?php
// allow-us-no-vpn.php
// Require/include this at the very top of your header/bootstrap so it runs before output.

// ========== CONFIG ==========
$IPQS_API_KEY = 'TSJZ8YasOgsAgSrreTeE12kN8XzMwFWs'; // <-- replace with your API key
$IPQS_API_URL = 'https://ipqualityscore.com/api/json/ip';
$CACHE_TTL = 600; // seconds (10 minutes)
$USE_CLOUDFLARE_HEADER = true; // set false if you don't use Cloudflare
// ============================

// Helper: get client's "real" IP
function get_real_ip() {
    // If behind Cloudflare, use CF-Connecting-IP
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    // X-Forwarded-For may contain multiple IPs
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

// Cloudflare country header (fast)
function country_from_cf() {
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        return strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
    }
    return null;
}

// Simple file cache for IP results
function cache_get($key) {
    $f = sys_get_temp_dir() . '/ip_check_' . md5($key);
    if (file_exists($f) && (time() - filemtime($f) < $GLOBALS['CACHE_TTL'])) {
        $c = @file_get_contents($f);
        return $c === false ? null : unserialize($c);
    }
    return null;
}
function cache_set($key, $value) {
    $f = sys_get_temp_dir() . '/ip_check_' . md5($key);
    @file_put_contents($f, serialize($value));
}

// Call IPQS API (curl) with timeout and error handling
function ipqs_check($api_key, $ip) {
    $cacheKey = "ipqs_{$ip}";
    $cached = cache_get($cacheKey);
    if ($cached !== null) return $cached;

    $url = "{$GLOBALS['IPQS_API_URL']}/{$api_key}/{$ip}?strictness=1"; 
    // strictness=1 => more strict bot/proxy detection. You can adjust.

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'IPQS-PHP-Checker/1.0');
    // Optional: set CA bundle path if curl complains (on some hosts)
    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false || $httpcode !== 200) {
        // On any error, return null to indicate failure
        return null;
    }

    $data = json_decode($resp, true);
    if (!is_array($data)) return null;

    cache_set($cacheKey, $data);
    return $data;
}

// Main decision function: returns true if allowed
function is_allowed() {
    // 1) CF header fast-check (if enabled)
    if ($GLOBALS['USE_CLOUDFLARE_HEADER']) {
        $cf_country = country_from_cf();
        if ($cf_country === null) {
            // no CF header present; we'll continue to IPQS check below using real IP
        } else {
            if ($cf_country !== 'US') {
                // Not US -> block immediately
                return false;
            }
            // CF says US => proceed to IPQS to detect VPN/proxy
        }
    }

    // 2) get IP and check with IPQS
    $ip = get_real_ip();
    if (!$ip) return false;

    $result = ipqs_check($GLOBALS['IPQS_API_KEY'], $ip);

    // If API failed/unavailable -> safer to block (you can change this to allow if wanted)
    if ($result === null) {
        return false;
    }

    // Check country from API (fallback)
    $country = strtoupper($result['country_code'] ?? '');
    if ($country !== 'US') {
        return false;
    }

    // IPQS flags that indicate proxy/VPN/datacenter/tor
    $is_proxy = !empty($result['proxy']) ? true : false;
    $is_vpn   = !empty($result['vpn']) ? true : false;
    $is_tor   = !empty($result['tor']) ? true : false;
    $is_datacenter = !empty($result['hosting_provider']) ? true : false; // hosting provider/datacenter
    $fraud_score = isset($result['fraud_score']) ? intval($result['fraud_score']) : 0;

    // Decision rules:
    // Block if any privacy/service flags true OR high fraud_score
    if ($is_proxy || $is_vpn || $is_tor || $is_datacenter) {
        return false;
    }

    // You can also block for high fraud_score (example threshold 75)
    if ($fraud_score >= 75) {
        return false;
    }

    // Passed all checks -> allow
    return true;
}

// Enforce: if not allowed -> block with 403 and message
if (!is_allowed()) {
    // Optional: show custom HTML page instead of plain text
    http_response_code(403);
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Access Restricted</title></head><body style='font-family:Arial, sans-serif; text-align:center; padding:40px;'>
          <h1>Access Restricted</h1>
          <p>This website is available only to visitors located in the United States using a normal residential connection. VPNs, proxies, datacenters and TOR are not allowed.</p>
          </body></html>";
    exit;
}
// else allowed -> continue to site
