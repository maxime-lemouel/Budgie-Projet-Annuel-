/**
 * Budgie — Modal manager
 * Basé sur l'élément <dialog> natif (~40 lignes, zéro dépendance)
 *
 * Usage HTML :
 *   Ouvrir   : <button data-modal-open="mon-modal">
 *   Fermer   : <button data-modal-close>  (dans le dialog)
 *   Auto     : <dialog id="..." data-modal-auto>  (ouvert au chargement — erreurs PHP)
 */
(function () {
    'use strict';

    function openModal(id) {
        const dialog = document.getElementById(id);
        if (dialog && !dialog.open) {
            dialog.showModal();
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(dialog) {
        if (dialog && dialog.open) {
            dialog.close();
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Boutons d'ouverture
        document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openModal(btn.dataset.modalOpen);
            });
        });

        // Boutons de fermeture
        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeModal(btn.closest('dialog'));
            });
        });

        // Clic sur le backdrop (zone hors .modal) ferme la dialog
        document.querySelectorAll('dialog').forEach(function (dialog) {
            dialog.addEventListener('click', function (e) {
                if (e.target === dialog) closeModal(dialog);
            });
            // Restaurer le scroll quand la dialog se ferme (y compris via Échap)
            dialog.addEventListener('close', function () {
                document.body.style.overflow = '';
            });
        });

        // Auto-ouvrir si PHP a renvoyé des erreurs ou si on est en mode création/édition
        document.querySelectorAll('dialog[data-modal-auto]').forEach(function (dialog) {
            dialog.showModal();
            document.body.style.overflow = 'hidden';
        });

    });

    // Exposer globalement pour usage inline
    window.openModal  = openModal;
    window.closeModal = closeModal;

}());