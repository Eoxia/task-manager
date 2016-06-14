<?php

if ( !defined( 'ABSPATH' ) ) exit;

class help_action_01 {
	public function __construct() {
    add_action( 'wp_ajax_ed_get_list_task', array( $this, 'callback_ed_get_list_task' ) );
	}

  public function callback_ed_get_list_task() {
    global $task_controller;
    $list_task = $task_controller->index( array( 'post_parent' => 0 ) );

    $list_task_json = array(
      'type' => 'listbox',
      'name' => 'task_id',
      'label' => __( 'Task', 'task-manager' ),
      'values' => array()
    );

    if ( !empty( $list_task ) ) {
      foreach ( $list_task as $element ) {
        $list_task_json['values'][] = array(
          "text" => '#' . $element->id . ' - ' . $element->title,
          "value" => $element->id
        );
      }
    }

    wp_send_json_success( array( 'list_task' => $list_task_json ));
  }
}

new help_action_01();
