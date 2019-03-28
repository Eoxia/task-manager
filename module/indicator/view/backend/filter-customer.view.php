
<?php

?>

<div class="form-element">
  <span class="form-label"><i class="fas fa-shopping-basket"></i> <?php esc_html_e( 'Which customer', 'task-manager' ); ?></span>
  <label class="form-field-container">
    <?php $customer_ctr->customer_select( $selected_customer_id ); ?>
  </label>
  <input type="hidden" name="page" value="<?= $page ?>" >
</div>
