import serializeFormData, { isFormChanged } from '../../shared/serialize'

let activeForm = null
let initialForm = ''

document.addEventListener('wpfb-form-saved', (e) => {
    if (e.detail.form === activeForm) {
        initialForm = serializeFormData(new FormData(activeForm))
        console.log('🆗 initialFormData обновлена после сохранения')
    }
})

/**
 * Opens drawer, loads HTML forms, and sends the wpfb‑drawer-loaded event.
 * @param {string} editUrl URL post.php/post‑new.php
 */
export async function openDrawer(editUrl) {
    const drawer = document.getElementById('wpfb-drawer')
    const overlay = document.getElementById('wpfb-overlay')
    const content = document.getElementById('wpfb-drawer-content')

    drawer.classList.add('open')
    overlay.style.display = 'block'
    content.innerHTML = '<div class="drawer-loading-message">Загрузка формы…</div>'

    try {
        const html = await fetch(editUrl).then((r) => r.text())
        const tmp = document.createElement('div')
        tmp.innerHTML = html

        activeForm = tmp.querySelector('#post')
        if (!activeForm) {
            throw new Error('HTML без формы')
        }

        content.replaceChildren(activeForm)

        /* 🔔 we inform auxiliary scripts (tabs, preview, etc.) */
        document.dispatchEvent(new CustomEvent('wpfb-drawer-loaded', { detail: { root: content } }))

        initialForm = serializeFormData(new FormData(activeForm))

        /* the × button */
        document.getElementById('wpfb-drawer-close').addEventListener('click', () => closeDrawer(true))
    } catch (err) {
        content.innerHTML = '<p class="error">Ошибка загрузки формы.</p>'
        console.warn(err)
    }

    /* Esc — close */
    document.addEventListener('keydown', escHandler)
}

function escHandler(e) {
    if (e.key === 'Escape') closeDrawer(false)
}

/**
 * Closes drawer; offers to save if necessary.
 * @param {boolean} askToSave
 */
export function closeDrawer(askToSave) {
    const drawer = document.getElementById('wpfb-drawer')
    const overlay = document.getElementById('wpfb-overlay')

    if (activeForm) {
        // eslint-disable-next-line no-restricted-globals,no-alert
        if (!askToSave || (isFormChanged(activeForm, initialForm) && confirm('Сохранить изменения перед закрытием?'))) {
            activeForm.dispatchEvent(new Event('submit', { cancelable: true }))
        }
        activeForm = null
    }
    drawer.classList.remove('open')
    overlay.style.display = 'none'
    document.removeEventListener('keydown', escHandler)
    /* shifting the focus back to the list */
}
