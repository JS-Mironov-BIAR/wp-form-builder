import changeActiveTile from './tileEvents'

export default function initTileHandler() {
    const tiles = document.querySelectorAll('.wpfb-method-tile')

    tiles.forEach((tile) => {
        tile.addEventListener('click', () => changeActiveTile(tile))
    })
}
