/**
 * Shows the toast message in the #wpfb-toast-container element.
 * @param {string} msg
 * @param {"success"|"error"} [type="success"]
 */
export default function showToast(msg, type = 'success') {
    const container = document.getElementById('wpfb-toast-container')
    if (!container) return

    const el = document.createElement('div')
    el.className = `wpfb-toast wpfb-toast--${type}`
    el.textContent = msg
    container.appendChild(el)

    setTimeout(() => el.classList.add('wpfb-toast-hide'), 3_000)
    setTimeout(() => el.remove(), 4_000)
}
