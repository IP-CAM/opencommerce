<div class="panel panel-default">
  <div class="panel-heading">{{ heading_title }}</div>
  <p style="text-align: center;">{{ text_store }}</p>
 {% for store in stores %}
  {% if store['store_id'] == $store_id %}
  <a href="{{ store.url }}"><b>{{ store.name }}</b></a><br />
  {% else %}
  <a href="{{ store.url }}">{{ store.name }}</a><br />
  <?php } ?>
  <?php } ?>
  <br />
</div>
