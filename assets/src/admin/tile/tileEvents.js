export default function changeActiveTile(tile) {
    const methodTile = document.querySelectorAll('.wpfb-method-tile')

    methodTile.forEach((t) => t.classList.remove('active'))
    tile.classList.add('active')
    tile.querySelector('input[type="radio"]').checked = true
}
