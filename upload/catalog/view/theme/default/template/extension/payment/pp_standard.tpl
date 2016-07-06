{% if testmode %}
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ text_testmode }}</div>
<?php } ?>
<form action="{{ action }}" method="post">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="upload" value="1" />
  <input type="hidden" name="business" value="{{ business }}" />
  <?php $i = 1; ?>
  {% for product in products %}
  <input type="hidden" name="item_name_{{ i }}" value="{{ product.name }}" />
  <input type="hidden" name="item_number_{{ i }}" value="{{ product.model }}" />
  <input type="hidden" name="amount_{{ i }}" value="{{ product.price }}" />
  <input type="hidden" name="quantity_{{ i }}" value="{{ product.quantity }}" />
  <input type="hidden" name="weight_{{ i }}" value="{{ product.weight }}" />
  <?php $j = 0; ?>
  {% for option in product.option %}
  <input type="hidden" name="on{{ j }}_{{ i }}" value="{{ option.name }}" />
  <input type="hidden" name="os{{ j }}_{{ i }}" value="{{ option.value }}" />
  <?php $j++; ?>
  <?php } ?>
  <?php $i++; ?>
  <?php } ?>
  {% if discount_amount_cart %}
    <input type="hidden" name="discount_amount_cart" value="{{ discount_amount_cart }}" />
  <?php } ?>
  <input type="hidden" name="currency_code" value="{{ currency_code }}" />
  <input type="hidden" name="first_name" value="{{ first_name }}" />
  <input type="hidden" name="last_name" value="{{ last_name }}" />
  <input type="hidden" name="address1" value="{{ address1 }}" />
  <input type="hidden" name="address2" value="{{ address2 }}" />
  <input type="hidden" name="city" value="{{ city }}" />
  <input type="hidden" name="zip" value="{{ zip }}" />
  <input type="hidden" name="country" value="{{ country }}" />
  <input type="hidden" name="address_override" value="0" />
  <input type="hidden" name="email" value="{{ email }}" />
  <input type="hidden" name="invoice" value="{{ invoice }}" />
  <input type="hidden" name="lc" value="{{ lc }}" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="charset" value="utf-8" />
  <input type="hidden" name="return" value="{{ return }}" />
  <input type="hidden" name="notify_url" value="{{ notify_url }}" />
  <input type="hidden" name="cancel_return" value="{{ cancel_return }}" />
  <input type="hidden" name="paymentaction" value="{{ paymentaction }}" />
  <input type="hidden" name="custom" value="{{ custom }}" />
  <input type="hidden" name="bn" value="OpenCart_2.0_WPS" />
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="{{ button_confirm }}" class="btn btn-primary" />
    </div>
  </div>
</form>
