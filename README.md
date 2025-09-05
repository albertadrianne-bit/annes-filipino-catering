# Anne’s Filipino Catering – WordPress Plugin

Custom WooCommerce extension for Anne’s Filipino Catering (Avondale, AZ).  
Built to handle catering bundles, request-a-quote workflows, guest count estimates, and a polished, single-page ordering experience for pick-up or delivery.

---

## ✨ Features

### Bundles
- **Party bundles** (Sakto Lang / Salo-Salo / Mega Handaan) + **Dessert-only** bundles.
- Admin can create **custom bundles** (Anne’s Catering → **Bundles**) with:
  - Tray count & default size
  - Premium item cap
  - % discount per bundle
  - Optional **category filter** (e.g., desserts only)
- Bundle modal: choose dishes, see **live subtotal → discount → total**, then **Add bundle to cart**.

### Quick-View Product Modal
- “**Choose Options**” on product cards opens a modal with **variations** (protein, spice level, tray size).
- **Add to Cart** directly from the modal (no page reload).

### Checkout & Quote Flow
- Sticky mini-cart with **56×56** rounded thumbnails and **× remove**.
- **Checkout modal** shows pick-up/delivery recap and **deposit math** (configurable %).
- **Request a Quote** button (toggle in Settings) uses the same cart/selection flow and stores quote details for follow-up.

### Guest Count Estimator
- Shortcode: `[annesfs_guest_estimator]`
- Recommends a tray mix based on **guest count** and meal style (**Hearty / Standard / Light**) with a rice/noodle adjustment.
- Optional floating widget (bottom-left) toggled in Settings.

### Dynamic Discount Tiers (Cart-wide)
- Tiered % off based on total **item quantity** (defaults: 5/7/9+ with 10/12/15%).
- Fully configurable in Settings.

### Badges & Tooltips
- **Category → badge mapping** (Classic / Premium / Elite) by slug.
- Optional tooltips for tray sizes / serving guidance.

### Delivery & Thresholds
- Flat delivery fee and **free delivery over** threshold.
- (Optional) ZIP eligibility rules and minimums (pickup vs delivery).

---

## ⚙️ Installation

1. Download the latest **release (.zip)** from GitHub (Releases → Assets).  
2. WordPress → Plugins → Add New → Upload Plugin → activate.  
3. Go to **Anne’s Catering → Settings** and click **Save** once to initialize.

---

## 🔧 Configuration (Settings)

- **Deposit percent** (default 50%)  
- **Delivery fee** & **Free over** threshold  
- **Discount tiers** (enable + set Qty/%)  
- **Guest Estimator** JSON portions + rice/noodle adjustment + floating widget toggle  
- **Badge mapping** for Classic/Premium/Elite category slugs  
- **Tooltips** copy toggle & text  
- **Request-a-Quote** enable/disable

---

## 🧩 Shortcodes

- `[annesfs_guest_estimator]` → Guest-to-tray estimator block (and optional floating widget via Settings)

---

## 📝 Version

- **Plugin Version:** `2.2.4-stable`

---

## 🧪 Requirements

- WordPress 6.0+  
- WooCommerce 8.0+  
- PHP 7.4+

---

## 🚀 Releases

Use GitHub **Tags/Releases** to generate permanent ZIPs for installation.
