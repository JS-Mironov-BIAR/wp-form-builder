import bindTextarea from './textareaTools'

/**
 * Initialization of the "Message Template" block.
 * @param {ParentNode} root
 */
export default function initMessageTools(root) {
    const textarea = root.querySelector('#wpfb-message-editor')
    if (!textarea) return

    bindTextarea({
        textarea,
        insertButtons: root.querySelectorAll('.wpfb-insert-message-tag'),
        undoBtn: root.querySelector('#wpfb-msg-undo-btn'),
        redoBtn: root.querySelector('#wpfb-msg-redo-btn'),
    })
}
