<?php
declare(strict_types=1);

/*
 * BOXAR'S EDGE — Stripe purchase verification
 *
 * Configure these values before going live:
 *   STRIPE_SECRET_KEY: your LIVE secret key, preferably via an environment variable.
 *   STRIPE_PAYMENT_LINK_ID: the plink_... ID for the $9.99 product.
 *
 * Do NOT put your Stripe secret key in index.html or any public JavaScript.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$secret = getenv('STRIPE_SECRET_KEY') ?: 'PASTE_LIVE_STRIPE_SECRET_KEY_HERE';
$expectedPaymentLink = getenv('STRIPE_PAYMENT_LINK_ID') ?: 'PASTE_STRIPE_PAYMENT_LINK_ID_HERE';

function fail_json(string $message, int $code=400): never {
    http_response_code($code);
    echo json_encode(['paid'=>false,'message'=>$message]);
    exit;
}

$sessionId = $_GET['session_id'] ?? '';
if (!preg_match('/^cs_[A-Za-z0-9_]+$/', $sessionId)) {
    fail_json('Invalid payment session.');
}
if (str_starts_with($secret, 'PASTE_') || str_starts_with($expectedPaymentLink, 'PASTE_')) {
    fail_json('Stripe server configuration is incomplete.', 500);
}

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . rawurlencode($sessionId));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $secret,
        'Accept: application/json'
    ],
    CURLOPT_TIMEOUT => 15
]);
$body = curl_exec($ch);
$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false || $http < 200 || $http >= 300) {
    fail_json('Stripe could not verify this payment.', 502);
}

$session = json_decode($body, true);
if (!is_array($session)) fail_json('Invalid Stripe response.', 502);

$paid = ($session['payment_status'] ?? '') === 'paid';
$amount = (int)($session['amount_total'] ?? 0);
$currency = strtolower((string)($session['currency'] ?? ''));
$paymentLink = (string)($session['payment_link'] ?? '');

if (!$paid || $amount !== 999 || $currency !== 'usd' || $paymentLink !== $expectedPaymentLink) {
    fail_json('This session does not contain a valid BOXAR’S EDGE $9.99 purchase.', 403);
}

/*
 * Keep the actual files outside direct public access when possible.
 * download.php performs the same Stripe verification before streaming them.
 */
echo json_encode([
    'paid' => true,
    'ui_url' => './download.php?file=ui&session_id=' . rawurlencode($sessionId),
    'pc_package_url' => './download.php?file=pc&session_id=' . rawurlencode($sessionId)
]);
