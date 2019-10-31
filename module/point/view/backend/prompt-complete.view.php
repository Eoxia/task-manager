<?php
/**
 * Parcours toutes les tâches et appel la vue "task".
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-modal modal-prompt-point">
    <div class="modal-container">

        <!-- Corps -->
        <div class="modal-content wpeo-form">
			<input type="hidden" name="post_id" value="0" />
			<input type="hidden" name="point_id" value="0" />
			<input type="hidden" name="complete" value="true" />
			<input type="hidden" name="by_prompt" value="true" />

			<p>Le point <span class="content">{{content}}</span> <strong>ne contient pas de commentaire.</strong></p>
			<p>Vous pouvez tout de même le compléter avec le formulaire suivant:</p>

            <div class="wpeo-gridlayout grid-4">
                <div class="gridw-3">
                    <div class="form-element comment-element">
                        <span class="form-label">Commentaire</span>
                        <label class="form-field-container">
                            <input type="text" class="form-field" name="content" />
                        </label>
                    </div>
                </div>

                <div>
                    <div class="form-element">
                        <span class="form-label">Temps</span>
                        <label class="form-field-container">
                            <input type="text" class="form-field" name="time" />
                        </label>
                    </div>
                </div>
            </div>

        <!-- Footer -->
        <div class="modal-footer">
            <a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
            <a class="wpeo-button button-grey button-uppercase modal-close action-input"
				data-action="complete_point"
				data-parent="modal-prompt-point"><span>Compléter sans commentaire</span></a>
            <a class="wpeo-button button-main button-uppercase modal-close action-input"
				data-action="complete_point"
			   	data-comment="true"
				data-parent="modal-prompt-point"><span>Compléter avec commentaire</span></a>
        </div>
    </div>
</div>
