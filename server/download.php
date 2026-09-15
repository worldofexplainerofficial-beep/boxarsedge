<?php
declare(strict_types=1);

/*
 * Protected digital delivery.
 * Place the actual files in a protected directory that is not directly web-accessible.
 * Recommended cPanel layout:
 *
 * public_html/
 *   index.html
 *   purchase-success.html
 *   verify-purchase.php
 *   download.php
 *   protected/
 *     Boxars_edge_UI.html
 *     BOXARS_EDGE_PC.zip
 *
 * Add a deny rule to protected/.htaccess.
 */

$secret = getenv('STRIPE_SECRET_KEY') ?: 'PASTE_LIVE_STRIPE_SECRET_KEY_HERE';
$expectedPaymentLink = getenv('STRIPE_PAYMENT_LINK_ID') ?: 'PASTE_STRIPE_PAYMENT_LINK_ID_HERE';
$sessionId = $_GET['session_id'] ?? '';
$file = $_GET['file'] ?? '';

if (!preg_match('/^cs_[A-Za-z0-9_]+$/', $sessionId) || !in_array($file, ['ui','pc'], true)) {
    http_response_code(400); exit('Invalid request.');
}
if (str_starts_with($secret, 'PASTE_') || str_starts_with($expectedPaymentLink, 'PASTE_')) {
    http_response_code(500); exit('Stripe server configuration is incomplete.');
}

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . rawurlencode($sessionId));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $secret, 'Accept: application/json'],
    CURLOPT_TIMEOUT => 15
]);
$body = curl_exec($ch);
$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false || $http < 200 || $http >= 300) {
    http_response_code(502); exit('Stripe verification failed.');
}

$session = json_decode($body, true);
if (!is_array($session) ||
    ($session['payment_status'] ?? '') !== 'paid' ||
    (int)($session['amount_total'] ?? 0) !== 999 ||
    strtolower((string)($session['currency'] ?? '')) !== 'usd' ||
    (string)($session['payment_link'] ?? '') !== $expectedPaymentLink) {
    http_response_code(403); exit('Purchase not verified.');
}

$root = __DIR__ . '/protected/';
$path = $file === 'ui' ? $root . 'Boxars_edge_UI.html' : $root . 'BOXARS_EDGE_PC.zip';

if (!is_file($path)) {
    http_response_code(404); exit('Digital product file is not installed on the server yet.');
}

$downloadName = basename($path);
header('Content-Type: ' . ($file === 'ui' ? 'text/html; charset=utf-8' : 'application/zip'));
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($path));
header('Cache-Control: private, no-store');
readfile($path);
exit;
