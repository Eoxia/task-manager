<?php
/**
 * Recherche parmis les clients
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.10.0
 * @version 1.10.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="form-element">
  <span class="form-label"><i class="fas fa-shopping-basket"></i> <?php esc_html_e( 'Which customer', 'task-manager' ); ?></span>
  <label class="form-field-container">
    <?php $customer_ctr->customer_select( $selected_customer_id ); ?>
  </label>
  <input type="hidden" name="page" value="<?= $page ?>" >
</div>
