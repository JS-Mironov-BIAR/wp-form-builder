import { openDrawer } from './drawer'

export async function refreshFormList() {
    const html = await fetch(window.location.href).then((r) => r.text())
    const tmp = document.createElement('div')
    tmp.innerHTML = html

    const src = tmp.querySelector('#wpfb-form-list')
    const dst = document.querySelector('#wpfb-form-list')
    if (src && dst) {
        dst.innerHTML = src.innerHTML
        attachEditHandlers() // re-hung
    }
}

export function attachEditHandlers() {
    document.querySelectorAll('[data-wpfb-form-edit]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            openDrawer(btn.dataset.wpfbFormEdit)
        })
    })
}
