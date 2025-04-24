/**
 * Converts our pseudo‑template to HTML for preview.
 * @param {string} tpl
 * @return {string}
 */
export default function parseTemplate(tpl) {
    const wrapWithLabel = (tag, attrs, { isTextarea = false, isCheckbox = false } = {}) => {
        const labelMatch = attrs.match(/label="([^"]+)"/)
        const label = labelMatch ? labelMatch[1] : null
        const isRequired = /required(\s*=\s*"?(true|1)?"?)?/.test(attrs)
        const requiredMark = isRequired ? '<span style="color:#e11d48;margin-left:4px">*</span>' : ''
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
            ? `<div style="margin-bottom:10px"><label style="display:flex;flex-direction:column;gap:4px"><span>${label}${requiredMark}</span>${inputHtml}</label></div>`
            : `<div style="margin-bottom:10px">${inputHtml}</div>`
    }

    return tpl
        .replace(/\[text (.*?)\]/g, (_, a) => wrapWithLabel('type="text"', a))
        .replace(/\[tel (.*?)\]/g, (_, a) => wrapWithLabel('type="tel"', a))
        .replace(/\[email (.*?)\]/g, (_, a) => wrapWithLabel('type="email"', a))
        .replace(/\[textarea (.*?)\]/g, (_, a) => wrapWithLabel('', a, { isTextarea: true }))
        .replace(/\[checkbox (.*?)\]/g, (_, a) => wrapWithLabel('', a, { isCheckbox: true }))
        .replace(/\[send (.*?)\]/g, (_, attrs) => {
            const txt = attrs.match(/text="([^"]+)"/)?.[1] ?? 'Отправить'
            return `<div style="margin-bottom:10px"><button ${attrs}>${txt}</button></div>`
        })
        .replace(/\[select (.*?)\]/g, (_, attrs) => {
            const items =
                attrs
                    .match(/items="([^"]+)"/)?.[1]
                    ?.split(',')
                    .map((i) => i.trim()) ?? []
            const opts = items.map((i) => `<option>${i}</option>`).join('')
            const clean = attrs.replace(/items="[^"]*"/, '').trim()
            // eslint-disable-next-line max-len
            return `<div style="margin-bottom:10px"><select ${clean} style="border:1px solid #ccc;padding:.5rem;width:100%">${opts}</select></div>`
        })
}
