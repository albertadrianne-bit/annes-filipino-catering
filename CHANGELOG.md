# Changelog

All notable changes to this project will be documented here.

## [2.2.4-stable] – 2025-08-21
### Added
- **Bundle Builder UI** (Anne’s Catering → Bundles): tray count, default size, premium cap, % discount, category filter.
- **Bundle modal** (frontend): live subtotal → discount → total, Add Bundle to Cart.
- **Quick-View product modal** for variations (protein, spice, size) from catalog grid.
- **Guest Estimator** shortcode `[annesfs_guest_estimator]` with hearty/standard/light + rice/noodle adjustment.
- **Dynamic discount tiers** (cart-wide): defaults 5/7/9+ → 10/12/15% (configurable).
- **Mini-cart polish**: 56×56 rounded thumbnails, working “× remove”, threshold nudges.
- **Checkout modal** with pickup/delivery recap and deposit math (from Settings).
- **Request-a-Quote** toggle; uses same selection/cart context; logs for follow-up.
- **Category → badge mapping** (Classic/Premium/Elite) + optional tooltips.

### Changed
- Centralized settings page; saving once initializes defaults.
- Improved script/style loading; lighter CSS hooks for Cornerstone styling.

### Fixed
- Various edge cases in mini-cart rendering and variation add-to-cart in modal.

---

## [2.1.6] – 2025-08-21
- Interim release (readme, assets scaffold, mini-cart adjustments).

## [2.1.5] – 2025-08-20
- Initial public repo sync (core structure, settings, quick-view, estimator baseline).
- 
## 2.1.6-dev – Aug 2025
- NEW: Dynamic discount tiers (by total cart quantity) with admin settings.
- NEW: Price-aware “Add to Cart” button inside the Quick-View modal (updates with variation & qty).
- Polish: groundwork for customer-side UX improvements.
  
## 2.1.5 – Aug 2025
- Added Guest Count Estimator shortcode.
- Added Quick-View modal with variation selection.
- Fixed missing assets (CSS/JS now included in zip).
- Stable base for testing customer workflows.

## 2.1.4 – Aug 2025
- Category → Badge mapping (configurable in Settings).
- Mini-cart image sizing polish (56×56 thumbnails, rounded corners).
- Stability improvements to cart and checkout modals.

## 2.1.3 – Aug 2025
- Fulfillment summary and deposit math refinements.
- Sticky cart UX improved.
- Backend polish and bug fixes.

## 2.1.2 – Aug 2025
- Request-a-Quote workflow improvements.
- Admin email/log formatting.
- Early bundle builder UX added.

## 2.1.1 – Aug 2025
- Deposit logic connected to Settings → Deposit Percent.
- Basic fulfillment summary nudges added.

## 2.0.x – Early Aug 2025
- Initial plugin skeleton.
- Added bundles, quote requests, thresholds.
- Delivery ZIP code validation.
