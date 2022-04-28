<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <Head :title="title"/>

    <jet-banner/>

<!--    desktop - tablet-->
    <div class="hidden sm:block">
        <app-layout-desktop>
            <template v-if="$slots.header" #header>
                <slot name="header"></slot>
            </template>
            <slot></slot>
        </app-layout-desktop>
    </div>

<!--    mobile-->
    <div class="sm:hidden">
        <app-layout-mobile>
            <template v-if="$slots.header" #header>
                <slot name="header"></slot>
            </template>
            <slot></slot>
        </app-layout-mobile>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
import JetBanner from '@/Jetstream/Banner.vue'
import JetDropdown from '@/Jetstream/Dropdown.vue'
import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
import JetNavLink from '@/Jetstream/NavLink.vue'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
import {Head, Link} from '@inertiajs/inertia-vue3'
import StarRating from "@/Components/StarRating";
import BoxedCheckbox from "@/Components/BoxedCheckbox";
import BoxedRadio from "@/Components/BoxedRadio";
import AppLayoutDesktop from "@/Layouts/AppLayoutDesktop";
import AppLayoutMobile from "@/Layouts/AppLayoutMobile";

export default defineComponent({
    props: {
        title: String,
    },

    components: {
        AppLayoutMobile,
        AppLayoutDesktop,
    },

    data() {
        return {}
    },

    computed: {},
    methods: {}
})
</script>

<style scoped>

.navbar {
    transition: all 330ms ease-out;
}

.navbar-open {
    transform: translateX(0%);
}

.navbar-close {
    transform: translateX(-100%);
}
</style>
