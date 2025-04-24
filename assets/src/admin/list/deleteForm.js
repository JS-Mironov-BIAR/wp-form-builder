/* global wpfb_admin */

import postJson from '../../shared/ajax'
import { refreshFormList } from './formList'
import showToast from '../../shared/toast'

export default function attachDeleteHandler() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.wpfb-delete-form')
        if (!btn) return

        const id = btn.dataset.id
        // eslint-disable-next-line no-restricted-globals,no-alert
        if (!id || !confirm(`Удалить форму #${id}?`)) return

        const row = btn.closest('tr')
        if (row?.style) {
            row.style.opacity = '0.4'
        }

        btn.disabled = true

        try {
            const json = await postJson(wpfb_admin.ajaxUrl, {
                action: 'wpfb_delete_form',
                post_id: id,
                _wpnonce: wpfb_admin.nonce,
            })

            if (json.success) {
                showToast('Форма удалена', 'success')
                await refreshFormList()
            } else {
                throw new Error(json.data || 'Ошибка удаления')
            }
        } catch (err) {
            showToast(err.message, 'error')
            if (row?.style) {
                row.style.opacity = '1'
            }

            btn.disabled = false
        }
    })
}
