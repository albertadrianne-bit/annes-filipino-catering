# Anne’s Filipino Catering – WordPress Plugin

Custom WooCommerce extension for Anne’s Filipino Catering.  
Built to handle catering bundles, request-a-quote workflows, guest count estimates, and polished customer experience.

---

## 🚀 Features
- **Bundle Builder**
  - Predefined bundles (Sakto Lang, Salo-Salo, Mega Handaan).
  - Supports Classic / Premium / Elite categories.
  - Auto-guest estimate per bundle size.

- **Request-a-Quote Flow**
  - Customers select dishes and submit quotes.
  - Admin receives structured email + logs in WP dashboard.
  - Deposit percent configurable in settings.

- **Quick-View Product Modal**
  - Customers click a dish card → modal shows variations (protein, spice, etc.).
  - Adds selected variation directly to cart/quote.

- **Badges & Styling**
  - Category → badge mapping (Classic, Premium, Elite).
  - Mini-cart images polished (56×56 thumbnails, rounded corners).

- **Checkout Modal**
  - Shows pickup/delivery summary + deposit breakdown.
  - Works alongside Stripe/Square gateway testing.

- **Guest Count Estimator**
  - Shortcode: `[annesfs_guest_estimator]`
  - Recommends tray count based on guest input.

- **Fulfillment & Admin Tools**
  - Minimum order thresholds (pickup vs delivery).
  - Delivery ZIP code validator.
  - Flat delivery fee option.
  - Seasonal/promo bundle support (future).

---

## ⚙️ Installation
1. Download the latest release (`.zip`) from GitHub.
2. Upload to WordPress via **Plugins → Add New → Upload Plugin**.
3. Activate the plugin.
4. Go to **Anne’s Catering → Settings** to configure:
   - Deposit percent
   - Category slugs for Classic/Premium/Elite
   - Delivery/pickup options

---

## 🖥️ Shortcodes
- `[annesfs_guest_estimator]` → Displays guest-to-tray estimator.

---

## 📌 Requirements
- WordPress 6.0+
- WooCommerce 8.0+
- PHP 7.4+

---

## 🛠️ Development Notes
- Version: **2.1.5**
- Next features: discount tier configurator, badge theme customization, code cleanup.
