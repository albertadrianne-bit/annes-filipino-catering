<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Lightweight “Checkout” modal shell.
 * We don’t replace gateways; we show a recap + deposit math,
 * then deep-link to Woo checkout OR keep this as a design shell
 * you can enhance later.
 */

add_action('wp_footer', function(){
  // Container
  ?>
  <div id="annesfs-checkout" class="annesfs-qv" style="display:none;">
    <div class="annesfs-qv__backdrop"></div>
    <div class="annesfs-qv__panel" role="dialog" aria-modal="true">
      <button type="button" class="annesfs-qv__close" aria-label="Close">×</button>
      <div class="annesfs-qv__content">
        <div class="qv-head">
          <div class="qv-title">Checkout Summary</div>
        </div>
        <div class="qv-body">
          <div>
            <h4>Order recap</h4>
            <?php if ( WC()->cart ) : ?>
              <ul style="margin:0;padding-left:18px;">
                <?php foreach ( WC()->cart->get_cart() as $ci ): $p=$ci['data']; ?>
                  <li><?php echo esc_html( $p->get_name() ); ?> × <?php echo intval($ci['quantity']); ?></li>
                <?php endforeach; ?>
              </ul>
              <p style="margin-top:8px;"><strong>Subtotal:</strong> <?php wc_cart_totals_subtotal_html(); ?></p>
            <?php else: ?>
              <p>Your cart is empty.</p>
            <?php endif; ?>
          </div>
          <div>
            <h4>Fulfillment</h4>
            <p id="annesfs-fulfill">Pickup / Delivery will be confirmed by our team.</p>
            <?php
              $dep = floatval(get_option('annesfs_deposit_percent',50));
              if ( WC()->cart ){
                $subtotal = WC()->cart->get_subtotal();
                $due_now = $subtotal * ($dep/100);
                echo '<p><strong>Deposit ('.esc_html($dep).'%):</strong> '.wc_price($due_now).'</p>';
              }
            ?>
            <p><a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button button-primary">Proceed to secure payment</a></p>
            <p style="font-size:12px;color:#6b7280;">Payment processing handled by your WooCommerce gateway (Stripe/Square). This modal is a polished recap so customers never feel “lost”.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
});

/** Add the “Checkout” button near mini-cart buttons (front-end). */
add_action('woocommerce_widget_shopping_cart_buttons', function(){
  echo '<a href="#" class="button annesfs-open-checkout" style="margin-left:8px;">Checkout</a>';
}, 25);

/** Tiny inline JS to open/close modal (reuses qv styles). */
add_action('wp_footer', function(){ ?>
  <script>
    (function(){
      function openCk(){ var el=document.getElementById('annesfs-checkout'); if(el){ el.style.display='block'; el.classList.add('is-open'); } }
      function closeCk(){ var el=document.getElementById('annesfs-checkout'); if(el){ el.classList.remove('is-open'); el.style.display='none'; } }
      document.addEventListener('click',function(e){
        if(e.target && e.target.classList && e.target.classList.contains('annesfs-open-checkout')){ e.preventDefault(); openCk(); }
        if(e.target && (e.target.classList.contains('annesfs-qv__backdrop') || e.target.classList.contains('annesfs-qv__close'))){ closeCk(); }
      });
    })();
  </script>
<?php });
