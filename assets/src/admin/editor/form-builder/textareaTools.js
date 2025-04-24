import updatePreview from './preview'
import HistoryStack from './historyStack'

/**
 * Connects the tag insertion buttons + Undo/Redo + Ctrl+Z/Y for textarea.
 * @param {Object} cfg
 * @param {HTMLTextAreaElement} cfg.textarea
 * @param {NodeListOf<HTMLButtonElement>} cfg.insertButtons
 * @param {HTMLButtonElement|null} cfg.undoBtn
 * @param {HTMLButtonElement|null} cfg.redoBtn
 * @param {HTMLElement|null} [cfg.preview]
 */
export default function bindTextarea({ textarea, insertButtons, undoBtn, redoBtn, preview }) {
    const history = new HistoryStack(textarea.value)

    // input = push + preview
    textarea.addEventListener('input', () => {
        history.push(textarea.value)
        updatePreview(textarea, preview)
    })

    // inserting a tag
    insertButtons.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            const tag = btn.dataset.tag ?? ''
            const start = textarea.selectionStart
            const end = textarea.selectionEnd
            textarea.value = textarea.value.slice(0, start) + tag + textarea.value.slice(end)
            textarea.focus()
            textarea.setSelectionRange(start + tag.length, start + tag.length)
            textarea.dispatchEvent(new Event('input'))
        })
    })

    // Undo / Redo
    undoBtn?.addEventListener('click', (e) => {
        e.preventDefault()
        const v = history.undo()
        if (v !== null) {
            textarea.value = v
            textarea.dispatchEvent(new Event('input'))
        }
    })
    redoBtn?.addEventListener('click', (e) => {
        e.preventDefault()
        const v = history.redo()
        if (v !== null) {
            textarea.value = v
            textarea.dispatchEvent(new Event('input'))
        }
    })

    // Ctrl+Z / Ctrl+Y
    textarea.addEventListener('keydown', (e) => {
        const isUndo = (e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey
        const isRedo = (e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))
        if (isUndo) {
            e.preventDefault()
            undoBtn?.click()
        }
        if (isRedo) {
            e.preventDefault()
            redoBtn?.click()
        }
    })

    // initial state
    updatePreview(textarea, preview)
}
