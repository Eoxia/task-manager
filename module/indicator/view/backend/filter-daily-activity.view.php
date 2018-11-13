<?php
/**
 * Vue des filtres des activités du jour.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   GPL2 <http://www.gnu.org/licenses/gpl-2.0.html>
 *
 * @package   Task_Manager\Templates
 *
 * @since     1.7.1
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<label>
    <?php esc_html_e( 'Which user', 'task-manager' ); ?>
    <select name="user_id_selected">
        <option value="0"><?php esc_html_e( 'All', 'task-manager' ); ?><option>
        <?php
        if ( ! empty( $users ) ) :
            foreach ( $users as $user ) :
                $selected = '';
                
                if ( $user->data['id'] === $selected_user_id ) :
                    $selected = 'selected="selected"';
                endif;
                ?>
                <option <?php esc_attr_e( $selected ); ?> value="<?php echo esc_attr( $user->data['id'] ); ?>"><?php echo esc_html( $user->data['displayname'] ); ?></option>
                <?php
            endforeach;
        endif;
        ?>
    </select>
</label>

<label>
    <i class="fa fa-user"></i>
    <?php esc_html_e( 'Which customer', 'task-manager' ); ?>
    <?php $customer_ctr->customer_select( $selected_customer_id ); ?>
</label> 

<button class="button-primary action-input" data-action="export_activity" data-parent="filter-activity"><?php esc_html_e( 'Export', 'task-manager' ); ?></button>