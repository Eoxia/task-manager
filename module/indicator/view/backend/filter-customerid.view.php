<?php
/**
 * Dans la page client, la recherche focus automatique le client
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
  <input type="hidden" name="user[customer_id]" value="<?= $client_id ?>" >
  <input type="hidden" name="page" value="<?= $page ?>" >
</div>
