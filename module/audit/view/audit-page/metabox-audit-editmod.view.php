<?php
/**
 * le mode édition qui permet de modifier la tache
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
} ?>

<div class="audit-title" contenteditable="false">
  <div class="form-element">
    <label class="form-field-container">
      <input class="form-field" name="title" value="<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : esc_html_e( 'No name Audit', 'task-manager' );  ?>" style="width: 200%;" />
    </label>
  </div>
</div>

<ul class="audit-summary">
  <li class="audit-summary-id"><i class="fas fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
  <li class="audit-summary-date">
    <div class="form-element group-date">
      <i class="fas fa-calendar-alt"></i>
      <label class="form-field-container wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Creation date', 'task-manager' ); ?>">
        <input type="hidden" class="mysql-date" name="date" value="<?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>">
        <input class="date form-field" type="text" value="<?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>">
      </label>
    </div>
  </li>
  <?php if( isset( $audit->data[ 'parent_id' ] ) && $audit->data[ 'parent_id' ] ): ?>
    <li class="tm-display-audit-parent-link">
        <span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>">
          <i class="fas fa-clone"></i>
          #<?php echo esc_html( $audit->data[ 'parent_id' ] ); ?> -
          <?php echo esc_html( $audit->data[ 'parent_title' ] ); ?>
        </span>
    </li>
		<?php if( ! isset( $parent_page ) || $parent_page == 0 ): ?>
	    <li class="tm-display-audit-parent-link">
	      <div class="tm-unlink-audit-parent"
	      data-id="<?php echo esc_html( $audit->data[ 'id' ] ); ?>"
	      data-nonce="<?php echo esc_attr( wp_create_nonce( 'delink_parent_to_audit' ) ); ?>"
	      data-action="delink_parent_to_audit"
	      style="cursor : pointer">
	        <i class="fas fa-unlink"></i>
	      </div>
	    </li>
	<?php endif; ?>

    <li class="tm-define-customer-to-audit" style="display : none">
  <?php else: ?>
    <li class="tm-define-customer-to-audit" style="display : block">
  <?php endif; ?>
      <div class="form-element">
        <i class="fas fa-clone"></i>
        <div class="form-fields">
          <input type="hidden" class="audit_search-customers-id" name="customer_id"/>
          <input type="text" class="audit-search-customers ui-autocomplete-input" placeholder="<?php echo esc_html( 'Nom/ID Client', 'task-manager'); ?>" autocomplete="new-password" />
        </div>
      </div>
  </li>
  <li>
    <span class="summary-rendered wpeo-tooltip-event tm-define-customer-to-audit-after" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>" style="display : none">
      <i class="fas fa-clone"></i>
	  </span>
	</li>

</ul>
