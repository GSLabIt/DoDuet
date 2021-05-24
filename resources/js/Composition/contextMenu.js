import {reactive} from "vue"

export default function contextMenu() {
    const context_menu = reactive({
        visible: false,
        id: null,
        x: 0,
        y: 0,
    })

    const openContextMenu = (event, id) => {
        context_menu.visible = true
        context_menu.id = id
        context_menu.x = event.layerX
        context_menu.y = event.layerY
    }

    const closeContextMenu = () => {
        context_menu.visible = false
    }

    return {
        context_menu,
        openContextMenu,
        closeContextMenu
    }
}
