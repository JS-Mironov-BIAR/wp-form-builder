import { rememberInitial } from './changeGuard'
import { refreshFormList } from '../list/formList'
import showToast from '../../shared/toast'

/**
 * Connects the submit handler to the #post form (root=document or drawer root).
 * @param {ParentNode} [root=document]
 */
export default function handleSave(root = document) {
    const form = root.querySelector('#post')
    if (!form || form.dataset.submitInit === '1') return
    form.dataset.submitInit = '1'

    rememberInitial(form)

    const submitDiv = root.querySelector('#submitdiv')

    form.addEventListener('submit', async (e) => {
        e.preventDefault()

        const fd = new FormData(form)
        const activeBtn = document.activeElement
        if (activeBtn?.name) fd.append(activeBtn.name, activeBtn.value)

        const saveBtn = form.querySelector('#publish,#save-post')
        const spinner = form.querySelector('.spinner')
        const origText = saveBtn?.value || 'Сохранить'

        if (saveBtn) {
            saveBtn.disabled = true
            saveBtn.value = 'Сохранение…'
        }
        spinner?.classList.add('is-active')

        try {
            //  const html = await fetch(form.action, { method: 'POST', body: fd }).then((r) => r.text())
            const actionUrl = form.getAttribute('action') || 'post.php'
            const html = await fetch(actionUrl, { method: 'POST', body: fd }).then((r) => r.text())

            /* ── ① обновляем боковой submit‑box, чтобы статус, кнопка и nonce стали свежими */
            if (submitDiv) {
                const tmp = document.createElement('div')
                tmp.innerHTML = html
                const freshSubmit = tmp.querySelector('#submitdiv')
                if (freshSubmit) submitDiv.replaceWith(freshSubmit)
            }

            const id = html.match(/post_ID["']?\s*value=["']?(\d+)/)?.[1]

            showToast('Форма успешно сохранена', 'success')
            if (id) await refreshFormList()
            rememberInitial(form)

            document.dispatchEvent(
                new CustomEvent('wpfb-form-saved', {
                    detail: { form },
                }),
            )
        } catch {
            showToast('Ошибка при сохранении формы', 'error')
            if (saveBtn) saveBtn.value = 'Ошибка'
        } finally {
            setTimeout(() => {
                if (saveBtn) {
                    saveBtn.disabled = false
                    saveBtn.value = origText
                }
                spinner?.classList.remove('is-active')
            }, 1_500)
        }
    })
}
