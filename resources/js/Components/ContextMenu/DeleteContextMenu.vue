<template>
    <li class="flex items-center hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 px-3 py-2 cursor-pointer"
        @click="requestDelete">
        <span class="far fa-trash-alt mr-3 text-red-500"></span>
        Rimuovi

        <confirmation-modal :show="deletion.confirm_box" @close="deletion.confirm_box = false">
            <template #title>
                Sei sicuro di voler rimuovere questo elemento?
            </template>
            <template #content>
                Per favore conferma che sei a conoscienza che la rimozione di questo elemento non è reversibile.
            </template>
            <template #footer>
                <div class="flex">
                    <div class="ml-auto mr-4 px-3 py-2 flex items-center justify-center text-red-400 rounded
                                transition-all duration-300 hover:bg-primary-300 hover:text-red-500 cursor-pointer"
                         @click="deletion.confirm_box = false">
                        Chiudi
                    </div>
                    <div class="bg-gray-700 rounded px-3 py-2 flex items-center justify-center
                                transition-all duration-300 hover:bg-gray-800 text-gray-100 cursor-pointer"
                         @click="confirmDelete">
                        Rimuovi elemento
                    </div>
                </div>
            </template>
        </confirmation-modal>
    </li>
</template>

<script>
import ConfirmationModal from "../../Jetstream/ConfirmationModal";

export default {
    name: "DeleteContextMenu",
    components: {
        ConfirmationModal,
    },
    props: {
        contextMenu: {
            type: Object,
            required: true
        },
        routeName: {
            type: String,
            required: true
        }
    },
    data: () => ({
        deletion: {
            confirm_box: false,
            confirmed: false,
        }
    }),
    methods: {
        confirmDelete() {
            this.deletion = {
                confirmed: true,
                confirm_box: false
            }
            this.requestDelete()
        },
        requestDelete() {
            if (this.contextMenu.visible && this.deletion.confirmed) {
                this.contextMenu.visible = false
                this.deletion.confirmed = false
                this.$inertia.delete(this.route(this.routeName, {
                    [this.parameterName]: this.contextMenu.id
                }), {
                    preserveScroll: true,
                    onSuccess() {
                        this.$inertia.reload({preserveScroll: true})
                    }
                })
            } else {
                this.deletion.confirm_box = true
            }
        },
    },
    computed: {
        /**
         * Computes the parameter name from the route name, note that this will work only if the default format is followed,
         * the format should be:
         *
         * <snake case controller name>-<something else>
         *
         * The parameter in the route should be named like the following example:
         * controller name: ThisIsATestController
         * route name: this_is_a_test_controller
         * parameter name thisIsATestController
         *
         * Basically it converts from snake to camel case
         *
         * @returns {string}
         */
        parameterName() {
            let name = this.routeName.split("-")[0],
                parts = name.split("_"),
                parameter = ""

            for(let [id, part] of parts.entries()) {
                parameter += `${id !== 0 ? part[0].toUpperCase() : part[0]}${part.substr(1)}`
            }

            return parameter
        }
    }
}
</script>

<style scoped>

</style>
