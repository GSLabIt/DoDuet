<template>
    <table class="w-full table-fixed">
        <thead class="select-none">
        <tr>
            <th v-for="(elem, id) of data.cols" :key="id"
                :class="[elem.class, { 'text-center': elem.centered, 'text-left': elem.left, 'text-right': elem.right }]">
                {{ elem.name }}
            </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(row, i) of data.values" :key="i" class="border-b border-secondary-100 last:border-none">
            <td v-for="(elem, id) of data.cols" :key="id"
                :class="{ 'text-center': elem.centered, 'text-left': elem.left, 'text-right': elem.right }">
                <slot v-if="$slots[`item.${elem.value}`]" :name="`item.${elem.value}`"
                      :value="explodeValue(row, elem.value)"
                      :index="i"></slot>
                <template v-else>
                    {{ explodeValue(row, elem.value) }}
                </template>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "DataTable",
    props: {
        data: {
            type: Object,
            validator(v) {
                return v.values && v.columns && Array.isArray(v.values) && Array.isArray(v.columns) &&
                    v.values.each(k => typeof k === "object" && !Array.isArray(k))
            }
        },
    },
    methods: {
        explodeValue(element, value) {
            let parts = value.split("."),
                res = element

            try {
                for (let elem of parts) {
                    res = res[elem]
                }
            } catch (e) {
                return false
            }


            return res
        },
    }
}
</script>

<style scoped>

</style>
