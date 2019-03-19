<?php
/**
 * Les actions relatives aux notes.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux notes.
 */
class Sticky_Note_Action {

	/**
	 * Initialise les actions liées aux notes.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		add_action( 'load-toplevel_page_wpeomtm-dashboard', array( $this, 'callback_load' ) );

		add_action( 'wp_ajax_edit_note', array( $this, 'callback_edit_note' ) );
		add_action( 'wp_ajax_add_note', array( $this, 'callback_add_note' ) );
		add_action( 'wp_ajax_delete_note', array( $this, 'callback_delete_note' ) );
	}

	/**
	 * Chargement
	 *
	 * @return void
	 */
	public function callback_load() {
		$notes = Sticky_Note_Class::g()->get(
			array(
				'author' => get_current_user_id(),
			)
		);

		if ( ! empty( $notes ) ) {
			foreach ( $notes as $i => $note ) {
				add_meta_box(
					'tm-indicator-notes-' . $note->data['id'],
					'&nbsp;',
					array( Sticky_Note_Class::g(), 'display' ),
					'wpeomtm-dashboard',
					'normal',
					'default',
					array(
						'note' => $note,
					)
				);
			}
		}

		add_meta_box(
			'tm-indicator-note-add',
			__( 'Add new sticky note', 'task-manager' ),
			array( Sticky_Note_Class::g(), 'display_add_new' ),
			'wpeomtm-dashboard',
			'normal',
			'default'
		);
	}

	/**
	 * Modification d'une note
	 *
	 * @return void
	 */
	public function callback_edit_note() {
		$note_id = ! empty( $_POST['note_id'] ) ? (int) $_POST['note_id'] : 0;
		$content = ! empty( $_POST['content'] ) ? $_POST['content'] : '';

		$content = str_replace( '<div>', '<br>', trim( $content ) );
		$content = wp_kses(
			$content,
			array(
				'br'      => array(),
				'tooltip' => array(
					'class' => array(),
				),
			)
		);

		if ( empty( $note_id ) ) {
			wp_send_json_error();
		}

		$note = Sticky_Note_Class::g()->get(
			array(
				'id' => $note_id,
			),
			true
		);

		$note->data['content'] = $content;

		Sticky_Note_Class::g()->update( $note->data );
		wp_send_json_success();
	}

	/**
	 * Ajout d'une note
	 *
	 * @return void
	 */
	public function callback_add_note() {
		$note = Sticky_Note_Class::g()->update( array() );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'sticky-note',
			'backend/sticky-note',
			array(
				'note' => $note,
			)
		);
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'stickyNote',
				'callback_success' => 'addedNote',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Met le status de la note en "trash".
	 *
	 * @since 1.8.0
	 */
	public function callback_delete_note() {
		check_ajax_referer( 'delete_note' );

		$note_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $note_id ) ) {
			wp_send_json_error();
		}

		$note = Sticky_Note_Class::g()->update(
			array(
				'id'     => $note_id,
				'status' => 'trash',
			)
		);

		do_action( 'tm_delete_note', $note );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'stickyNote',
				'callback_success' => 'deletedNoteSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}
}

new Sticky_Note_Action();
