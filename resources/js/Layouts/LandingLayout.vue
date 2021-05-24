<template>
    <div class="flex flex-col min-h-screen w-full max-w-screen bg-purple-100" id="home">
        <header class="flex shadow-md w-full py-2 sticky top-0 inset-x-0 z-40 md:px-2 lg:px-0 bg-gradient-to-t from-purple-100 to-purple-50">
            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-12 w-full mx-auto text-primary-600">
                <inertia-link :href="urls.home"
                              class="hidden w-full transition-all duration-300 items-center justify-center lg:flex col-start-2 col-span-2">
                    <img src="/asset/brand-dark.svg" alt="The Ditty Tune" class="object-contain h-full max-h-12">
                </inertia-link>
                <div class="hidden col-start-5 col-span-4 lg:grid grid-cols-4 text-primary-100">
                    <inertia-link v-for="(elem, id) of nav" :key="id" :href="urls[elem.route]"
                                  class="flex items-center justify-center py-4 px-4 transition-all duration-300 hover:bg-purple-200 rounded"
                                  :class="classIfRouteMatch(elem.active)">
                        {{ elem.label }}
                    </inertia-link>
                </div>
                <div class="flex lg:hidden items-center justify-center w-full h-full cursor-pointer" @click="toggleMenu">
                    <i class='bx bx-menu text-4xl'></i>
                </div>
                <div class="col-start-10 col-span-2 grid grid-cols-2 gap-4 text-secondary-800 font-semibold">
                    <inertia-link v-if="$page.props.user" :href="urls.dashboard"
                                  class="flex items-center justify-center rounded py-4 px-4 transition-all duration-300 hover:bg-purple-200"
                                  :class="classIfRouteMatch('dashboard')">
                        Dashboard
                    </inertia-link>

                    <template v-else>
                        <inertia-link :href="urls.login" class="flex items-center justify-center rounded py-4 px-4 transition-all duration-300 hover:bg-purple-200">
                            Log in
                        </inertia-link>

                        <inertia-link :href="urls.register" class="flex items-center justify-center rounded py-4 px-4 transition-all duration-500
                            bg-gradient-to-r from-rose-200 to-secondary-100 bg-64 bg-left hover:-bg-left-8">
                            Register
                        </inertia-link>
                    </template>
                </div>
            </div>
        </header>
        <div class="fixed top-0 left-0 right-0 bottom-0 h-screen min-h-screen max-h-screen max-w-screen w-full lg:hidden flex-col bg-primary-400
                transform-gpu transition-all duration-500 z-50 p-6"
             :class="mobile_menu">
            <div class="absolute top-0 right-6 p-6 flex items-center justify-center cursor-pointer text-primary-100" @click="toggleMenu">
                <i class='bx bx-x text-4xl'></i>
            </div>
            <inertia-link :href="urls.home"
                          class="flex w-1/3 transition-all duration-300 items-center mb-6">
                <img src="/asset/brand-dark.svg" alt="The Ditty Tune" class="object-contain h-full max-h-12">
            </inertia-link>
            <inertia-link v-for="(elem, id) of nav" :key="id" :href="urls[elem.route]"
                          class="flex items-center py-4 px-4 text-gray-100 border-b border-primary-100 transition-all duration-300"
                          :class="classIfRouteMatch(elem.active)">
                {{ elem.label }}
            </inertia-link>
        </div>

        <main class="text-primary-200">
            <slot></slot>
        </main>

        <footer class="bg-gradient-to-b from-purple-100 to-purple-50 mt-8 border-t border-secondary-100">
            <container>
                <ul class="grid grid-cols-3 md:grid-cols-8 p-4 md:p-8 gap-4 text-primary-100">
                    <li class="col-start-1 row-start-1 col-span-full md:col-span-2 flex items-center justify-center">
                        <inertia-link :href="urls.home"
                                      class="flex w-full transition-all duration-300 items-center justify-center py-4">
                            <img src="/asset/brand-dark.svg" alt="The Ditty Tune" class="object-contain h-full max-h-12">
                        </inertia-link>
                    </li>
                    <li class="row-start-2 lg:row-start-1 lg:col-start-4 col-start-1 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Contacts
                        </inertia-link>
                    </li>
                    <li class="row-start-2 lg:row-start-1 md:col-span-2 lg:col-span-1 lg:col-start-5 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Team
                        </inertia-link>
                    </li>
                    <li class="row-start-2 lg:row-start-1 md:col-span-2 lg:col-span-1 lg:col-start-6 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Sitemap
                        </inertia-link>
                    </li>
                    <li class="col-start-2 md:row-start-2 lg:row-start-1 md:col-start-8 flex items-center justify-center">
                        <a href="#" target="_blank" rel="noopener"
                                      class="flex w-full items-center justify-center font-semibold py-4 text-center mx-2">
                            <span class="fab fa-instagram text-lg"></span>
                        </a>
                        <a href="#" target="_blank" rel="noopener"
                                      class="flex w-full items-center justify-center font-semibold py-4 text-center mx-2">
                            <span class="fab fa-facebook text-lg"></span>
                        </a>
                        <a href="#" target="_blank" rel="noopener"
                                      class="flex w-full items-center justify-center font-semibold py-4 text-center mx-2">
                            <span class="fab fa-whatsapp text-lg"></span>
                        </a>
                    </li>
                </ul>
                <ul class="grid grid-cols-2 md:grid-cols-12 text-sm gap-2 text-primary-100">
                    <li class="row-start-4 md:col-span-12 md:row-start-2 lg:col-span-6 lg:row-start-1 col-span-2 flex flex-col md:flex-row items-center justify-center">
                        <div>&copy; {{ copyright }} duduet.studio</div>
                        <span class="mx-2 hidden md:block">|</span>
                        <div class="flex items-center justify-center mb-3 md:mb-0">
                            Made with <span class="bx bxs-heart text-red-500 text-base mx-2"></span> by Ebalo
                        </div>
                    </li>
                    <li class="md:col-span-4 lg:col-span-2 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Privacy policy
                        </inertia-link>
                    </li>
                    <li class="md:col-span-4 lg:col-span-2 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Cookie policy
                        </inertia-link>
                    </li>
                    <li class="md:col-span-4 lg:col-span-2 flex items-center justify-center">
                        <inertia-link href="#"
                                      class="flex w-full items-center justify-center font-semibold py-4">
                            Terms & Conditions
                        </inertia-link>
                    </li>
                </ul>
            </container>
        </footer>
    </div>
</template>

<script>
import Container from "../Components/Container";
import globalUrls from "../Composition/urls";

export default {
    name: "LandingLayout",
    components: {Container},
	setup() {
		return {
			...globalUrls(),
		}
	},
    data() {
        return {
            copyright: null,
            piva: "0000000000000000",
            menu_open: false,
            nav: [
                {
                    label: "Home",
                    route: "home",
                    active: "home",
                },
                {
                    label: "Tracks",
                    route: "tracks",
                    active: "#tracks",
                },
                {
                    label: "How it works?",
                    route: "how_it_works",
                    active: "#how-it-works",
                },
                {
                    label: "Prices",
                    route: "plans",
                    active: "#plans",
                },
            ]
        }
    },
    methods: {
        toggleMenu() {
            this.menu_open = !this.menu_open
        }
    },
    computed: {
        mobile_menu() {
            return !this.menu_open ?
                `-translate-x-full`
                :
                `translate-x-0 flex`
        },
    },
    created() {
        this.copyright = (new Date()).getFullYear()
    }
}
</script>

<style scoped>
</style>
