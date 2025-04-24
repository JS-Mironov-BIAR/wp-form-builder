/**
 * A POST request with a url‑encoded body, returns JSON.
 * @param {string} url
 * @param {Record<string,string>} data
 */
export default async function postJson(url, data) {
    const rsp = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data),
    })
    return rsp.json()
}
