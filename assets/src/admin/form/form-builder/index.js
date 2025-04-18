export default function initFormEditor() {
    const formTextarea = document.getElementById('wpfb-form-editor')
    const formPreview = document.getElementById('wpfb-form-preview')
    const formInsertButtons = document.querySelectorAll('.wpfb-insert-tag')
    const formUndoBtn = document.getElementById('wpfb-undo-btn')
    const formRedoBtn = document.getElementById('wpfb-redo-btn')

    const messageTextarea = document.getElementById('wpfb-message-editor')
    const messageInsertButtons = document.querySelectorAll('.wpfb-insert-message-tag')
    const messageUndoBtn = document.getElementById('wpfb-msg-undo-btn')
    const messageRedoBtn = document.getElementById('wpfb-msg-redo-btn')

    const formHistory = []
    const formRedoStack = []
    const messageHistory = []
    const messageRedoStack = []

    if (!formTextarea) return

    // ====== 1. Парсинг HTML шаблона формы
    const parseTemplate = (text) => {
        const wrapWithLabel = (tag, attrs, isTextarea = false, isCheckbox = false) => {
            const labelMatch = attrs.match(/label="([^"]+)"/)
            const label = labelMatch ? labelMatch[1] : null
            const isRequired = /required(?:\s*=\s*"?(true|1)?"?)?/.test(attrs)
            const requiredMark = isRequired ? '<span style="color:#e11d48; margin-left:4px;">*</span>' : ''
            const requiredAttr = isRequired ? ' aria-required="true"' : ''
            const cleanAttrs = attrs.replace(/label="[^"]*"/, '').trim()

            let inputHtml
            if (isTextarea) {
                inputHtml = `<textarea ${cleanAttrs}${requiredAttr}></textarea>`
            } else if (isCheckbox) {
                inputHtml = `<input type="checkbox" ${cleanAttrs}${requiredAttr}>`
            } else {
                inputHtml = `<input ${tag} ${cleanAttrs}${requiredAttr}>`
            }

            return label
                ? `<div style="margin-bottom:10px;"><label style="display:flex;flex-direction:column;gap:4px;"><span>${label}${requiredMark}</span>${inputHtml}</label></div>`
                : `<div style="margin-bottom:10px;">${inputHtml}</div>`
        }

        return text
            .replace(/\[text (.*?)\]/g, (_, attrs) => wrapWithLabel('type="text"', attrs))
            .replace(/\[tel (.*?)\]/g, (_, attrs) => wrapWithLabel('type="tel"', attrs))
            .replace(/\[email (.*?)\]/g, (_, attrs) => wrapWithLabel('type="email"', attrs))
            .replace(/\[textarea (.*?)\]/g, (_, attrs) => wrapWithLabel('', attrs, true))
            .replace(/\[checkbox (.*?)\]/g, (_, attrs) => wrapWithLabel('', attrs, false, true))
            .replace(/\[send (.*?)\]/g, (_, attrs) => {
                const textMatch = attrs.match(/text="([^"]+)"/)
                const btnText = textMatch ? textMatch[1] : 'Отправить'
                return `<div style="margin-bottom:10px;"><button ${attrs}>${btnText}</button></div>`
            })
            .replace(/\[select (.*?)\]/g, (_, attrs) => {
                const itemsMatch = attrs.match(/items="([^"]+)"/)
                let optionsHtml = ''

                if (itemsMatch) {
                    const items = itemsMatch[1].split(',').map(item => item.trim())
                    optionsHtml = items.map(item => `<option>${item}</option>`).join('')
                }

                const cleanAttrs = attrs.replace(/items="[^"]*"/, '').trim()

                return `<div style="margin-bottom:10px;"><select ${cleanAttrs} style="border: 1px solid #ccc; padding: 0.5rem; width: 100%;">${optionsHtml}</select></div>`
            })
    }

    const updateFormPreview = () => {
        if (formPreview) {
            formPreview.innerHTML = parseTemplate(formTextarea.value)

            // 🧹 Удаляем лишние атрибуты в предпросмотре
            formPreview.querySelectorAll('input, textarea, select').forEach((el) => {
                el.removeAttribute('required')
                el.removeAttribute('name')
                el.removeAttribute('disabled')
            })
        }
    }


    const saveFormHistory = () => {
        const current = formTextarea.value
        if (formHistory.length === 0 || formHistory[formHistory.length - 1] !== current) {
            formHistory.push(current)
            formRedoStack.length = 0
        }
    }

    const saveMessageHistory = () => {
        const current = messageTextarea.value
        if (messageHistory.length === 0 || messageHistory[messageHistory.length - 1] !== current) {
            messageHistory.push(current)
            messageRedoStack.length = 0
        }
    }

    // ====== 2. Слушатели событий для формы
    formTextarea.addEventListener('input', () => {
        saveFormHistory()
        updateFormPreview()
    })

    formInsertButtons.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            saveFormHistory()
            const tag = btn.dataset.tag
            const start = formTextarea.selectionStart
            const end = formTextarea.selectionEnd
            const current = formTextarea.value

            formTextarea.value = current.slice(0, start) + tag + current.slice(end)
            formTextarea.focus()
            formTextarea.setSelectionRange(start + tag.length, start + tag.length)
            formTextarea.dispatchEvent(new Event('input'))
        })
    })

    formUndoBtn?.addEventListener('click', (e) => {
        e.preventDefault()
        if (formHistory.length > 1) {
            formRedoStack.push(formHistory.pop())
            formTextarea.value = formHistory[formHistory.length - 1]
            formTextarea.dispatchEvent(new Event('input'))
        }
    })

    formRedoBtn?.addEventListener('click', (e) => {
        e.preventDefault()
        if (formRedoStack.length > 0) {
            const next = formRedoStack.pop()
            formHistory.push(next)
            formTextarea.value = next
            formTextarea.dispatchEvent(new Event('input'))
        }
    })

    formTextarea.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
            e.preventDefault()
            formUndoBtn?.click()
        }
        if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
            e.preventDefault()
            formRedoBtn?.click()
        }
    })

    // ====== 3. Слушатели событий для сообщения
    if (messageTextarea) {
        messageTextarea.addEventListener('input', () => {
            saveMessageHistory()
        })

        messageInsertButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault()
                saveMessageHistory()
                const tag = btn.dataset.tag
                const start = messageTextarea.selectionStart
                const end = messageTextarea.selectionEnd
                const current = messageTextarea.value

                messageTextarea.value = current.slice(0, start) + tag + current.slice(end)
                messageTextarea.focus()
                messageTextarea.setSelectionRange(start + tag.length, start + tag.length)
                messageTextarea.dispatchEvent(new Event('input'))
            })
        })

        messageUndoBtn?.addEventListener('click', (e) => {
            e.preventDefault()
            if (messageHistory.length > 1) {
                messageRedoStack.push(messageHistory.pop())
                messageTextarea.value = messageHistory[messageHistory.length - 1]
            }
        })

        messageRedoBtn?.addEventListener('click', (e) => {
            e.preventDefault()
            if (messageRedoStack.length > 0) {
                const next = messageRedoStack.pop()
                messageHistory.push(next)
                messageTextarea.value = next
            }
        })

        messageTextarea.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                e.preventDefault()
                messageUndoBtn?.click()
            }
            if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
                e.preventDefault()
                messageRedoBtn?.click()
            }
        })
    }

    // Init
    saveFormHistory()
    updateFormPreview()
    saveMessageHistory()
}
