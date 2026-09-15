BOXAR'S EDGE — Stripe $9.99 purchase flow

1. Create a Stripe Payment Link for $9.99 USD.
2. Set Stripe After Payment redirect to:
   https://YOUR-DOMAIN/purchase-success.html?session_id={CHECKOUT_SESSION_ID}
3. Put your LIVE Stripe secret key in the STRIPE_SECRET_KEY environment variable.
4. Put the Payment Link ID (plink_...) in STRIPE_PAYMENT_LINK_ID.
5. Upload:
   index.html
   purchase-success.html
   verify-purchase.php
   download.php
   protected/
6. Replace protected/PC_PACKAGE_REQUIRED.txt with the actual PC build ZIP named:
   BOXARS_EDGE_PC.zip

The supplied Boxars_edge_UI HTML has been placed in protected/Boxars_edge_UI.html.
Direct access to protected files is denied by .htaccess; download.php verifies the paid Stripe Checkout Session before delivery.

Important: do not put your Stripe secret key in client-side JavaScript.
