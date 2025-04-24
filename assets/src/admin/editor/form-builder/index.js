import bindTextarea from './textareaTools'
import initMessageTools from './messageTools'

/**
 * Initializes the message form and template editor.
 * Can be called many times (for drawer).
 *
 * @param {ParentNode} [root=document]
 */
export default function initFormBuilder(root = document) {
    const formTextarea = root.querySelector('#wpfb-form-editor')
    const formPreview = root.querySelector('#wpfb-form-preview')
    if (!formTextarea) return // the editor was not found

    bindTextarea({
        textarea: formTextarea,
        insertButtons: root.querySelectorAll('.wpfb-insert-tag'),
        undoBtn: root.querySelector('#wpfb-undo-btn'),
        redoBtn: root.querySelector('#wpfb-redo-btn'),
        preview: formPreview,
    })

    initMessageTools(root)
}
