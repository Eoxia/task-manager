/**
 * Initialise l'objet helloWorld dans le namespace eoFrameworkStarter.
 * Permet d'éviter les conflits entre les différents plugins utilisant EO-Framework.
 *
 * Ce fichier JS est une base pour utiliser du JS avec EO-Framework.
 * En lançant la commande "npm start", GULP vas écouter les fichiers *.backend.js et
 * vas s'occuper de les assembler dans le fichier backend.min.js.
 *
 * EO-Framework appel automatiquement la méthode "init" à l'initilisation de certains *pages*
 * du backadmin de WordPress. Ces pages doivent être définis dans le tableau "insert_scripts_page" dans le fichier *.config.json
 * principales de votre plugin.
 * @see https://github.com/Eoxia/task-manager/blob/master/task-manager.config.json pour un exemple
 *
 * @since 0.1.0
 * @version 0.1.0
 */
window.eoxiaJS.eoFrameworkStarter.helloWorld = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 0.1.0
 * @version 0.1.0
 *
 * @return {void}
 */
window.eoxiaJS.eoFrameworkStarter.helloWorld.init = function() {
	jQuery( '.wpeo-wrap iframe' ).css( 'height', jQuery( window ).height() + 'px' );
};
