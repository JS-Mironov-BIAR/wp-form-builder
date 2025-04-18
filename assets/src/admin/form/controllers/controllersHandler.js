/* global wpfb_admin */
import initFormEditor from '../form-builder'

let activeForm = null // 💾 Текущая форма
let initialFormData = ''

function serializeFormData(formData) {
    const entries = Array.from(formData.entries())
    entries.sort(([aKey], [bKey]) => aKey.localeCompare(bKey))
    return JSON.stringify(entries)
}

export default async function eventClickEditButton(e, button) {
    e.preventDefault()

    const drawer = document.getElementById('wpfb-drawer')
    const overlay = document.getElementById('wpfb-overlay')
    const closeButton = document.getElementById('wpfb-drawer-close')
    const content = document.getElementById('wpfb-drawer-content')
    const url = button.getAttribute('data-wpfb-form-edit')

    drawer.classList.add('open')
    overlay.style.display = 'block'
    content.innerHTML = `<div class="drawer-loading-message">Загрузка формы...</div>`

    try {
        const response = await fetch(url)
        const html = await response.text()

        const temp = document.createElement('div')
        temp.innerHTML = html

        const form = temp.querySelector('#post')
        if (form) {
            content.innerHTML = ''
            content.appendChild(form)

            handleFormSave(form)
            activeForm = form
            initFormEditor()
            initialFormData = serializeFormData(new FormData(form)) // 🆕
        } else {
            content.innerHTML = `<p class="error">Ошибка загрузки формы.</p>`
        }
    } catch {
        content.innerHTML = `<p class="error">Ошибка сети.</p>`
    }

    closeButton.addEventListener('click', () => handleCloseDrawer(true))
}

function isFormChanged(form) {
    const currentData = serializeFormData(new FormData(form))
    return currentData !== initialFormData
}

function handleCloseDrawer(withConfirm = true) {
    const drawer = document.getElementById('wpfb-drawer')
    const overlay = document.getElementById('wpfb-overlay')

    if (activeForm) {
        if (!withConfirm || (isFormChanged(activeForm) && confirm('Сохранить изменения перед закрытием?'))) {
            activeForm.dispatchEvent(new Event('submit', { cancelable: true }))
        }
        activeForm = null
    }

    drawer.classList.remove('open')
    overlay.style.display = 'none'
}


// ⎋ Escape: закрыть шторку
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        handleCloseDrawer(false)
    }
})

function handleFormSave(form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault()

        const formData = new FormData(form)

        // Учитываем кнопку, которая вызвала submit
        const activeButton = document.activeElement
        if (activeButton && activeButton.name) {
            formData.append(activeButton.name, activeButton.value)
        }

        const saveButton = form.querySelector('#publish') || form.querySelector('#save-post')
        const spinner = form.querySelector('.spinner')
        const originalText = saveButton?.value || 'Сохранить'

        if (saveButton) {
            saveButton.disabled = true
            spinner?.classList.add('is-active')
            saveButton.value = 'Сохранение...'
        }

        try {
            const actionUrl = form.getAttribute('action')
            const response = await fetch(actionUrl, {
                method: 'POST',
                body: formData,
            })

            if (!response.ok) throw new Error('Ошибка')

            const html = await response.text()

            const match = html.match(/post_ID["']?\s*value=["']?(\d+)/)
            const postId = match?.[1]

            showToast('Форма успешно сохранена', 'success')

            if (postId) {
                await refreshFormList()
            }

            initialFormData = serializeFormData(new FormData(form)) // 🆕 Обновляем сохранённые данные
        } catch (err) {
            showToast('Ошибка при сохранении формы', 'error')
            if (saveButton) saveButton.value = 'Ошибка'
        } finally {
            setTimeout(() => {
                if (saveButton) {
                    saveButton.disabled = false
                    spinner?.classList.remove('is-active')
                    saveButton.value = originalText
                }
            }, 1500)
        }
    })
}

async function refreshFormList() {
    try {
        const response = await fetch(window.location.href)
        const html = await response.text()

        const temp = document.createElement('div')
        temp.innerHTML = html

        const newList = temp.querySelector('#wpfb-form-list')
        const currentList = document.querySelector('#wpfb-form-list')

        if (newList && currentList) {
            currentList.innerHTML = newList.innerHTML

            reattachEditButtonHandlers()
        }
    } catch (e) {
        console.warn('Не удалось обновить список форм', e)
    }
}

function reattachEditButtonHandlers() {
    document.querySelectorAll('.wpfb-form-link').forEach((button) => {
        button.addEventListener('click', (e) => eventClickEditButton(e, button))
    })
}

function showToast(message, type = 'success') {
    const container = document.getElementById('wpfb-toast-container')
    if (!container) return

    const toast = document.createElement('div')
    toast.className = `wpfb-toast wpfb-toast--${type}`
    toast.textContent = message

    container.appendChild(toast)

    setTimeout(() => {
        toast.classList.add('wpfb-toast-hide')
    }, 3000)

    setTimeout(() => {
        toast.remove()
    }, 4000)
}

document.addEventListener('click', async (e) => {
    const button = e.target.closest('.wpfb-delete-form')
    if (!button) return

    const id = button.getAttribute('data-id')
    if (!id || !confirm(`Удалить форму #${id}?`)) return

    const row = button.closest('tr')
    if (row) {
        row.style.opacity = '0.4'
        button.disabled = true
    }

    try {
        const response = await fetch(wpfb_admin.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'wpfb_delete_form',
                post_id: id,
                _wpnonce: wpfb_admin.nonce,
            }),
        })

        const json = await response.json()

        if (json.success) {
            showToast('Форма удалена', 'success')

            // ✅ Альтернатива 1: обновляем весь список
            await refreshFormList()

            // ✅ Альтернатива 2 (вместо refreshFormList): удаляем строку напрямую
            // if (row) row.remove()
        } else {
            showToast(json.data || 'Ошибка удаления', 'error')
            if (row) {
                row.style.opacity = '1'
                button.disabled = false
            }
        }
    } catch (err) {
        showToast('Сетевая ошибка', 'error')
        if (row) {
            row.style.opacity = '1'
            button.disabled = false
        }
    }
})
