<!--
  - Copyright (c) 2022 - Do Group LLC - All Right Reserved.
  - Unauthorized copying of this file, via any medium is strictly prohibited
  - Proprietary and confidential
  - Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
  -->

<template>
    <Head :title="title"/>

    <jet-banner/>
    <div class="flex flex-col h-screen">
        <!--header-->
        <nav class="h-16 text-gray-200 bg-[#0A101F]">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('authenticated.dashboard')">
                                <jet-application-mark class="block h-9 w-auto"/>
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            <!-- Teams Dropdown -->
                            <jet-dropdown align="right" width="60" v-if="$page.props.jetstream.hasTeamFeatures">
                                <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                                {{ $page.props.user.current_team.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <div class="w-60">
                                        <!-- Team Management -->
                                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Manage Team
                                            </div>

                                            <!-- Team Settings -->
                                            <jet-dropdown-link
                                                :href="route('teams.show', $page.props.user.current_team)">
                                                Team Settings
                                            </jet-dropdown-link>

                                            <jet-dropdown-link :href="route('teams.create')"
                                                               v-if="$page.props.jetstream.canCreateTeams">
                                                Create New Team
                                            </jet-dropdown-link>

                                            <div class="border-t border-gray-100"></div>

                                            <!-- Team Switcher -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Switch Teams
                                            </div>

                                            <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                                <form @submit.prevent="switchToTeam(team)">
                                                    <jet-dropdown-link as="button">
                                                        <div class="flex items-center">
                                                            <svg
                                                                v-if="team.id == $page.props.user.current_team_id"
                                                                class="mr-2 h-5 w-5 text-green-400" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <div>{{ team.name }}</div>
                                                        </div>
                                                    </jet-dropdown-link>
                                                </form>
                                            </template>
                                        </template>
                                    </div>
                                </template>
                            </jet-dropdown>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <jet-dropdown align="right" width="48">
                                <template #trigger>
                                    <button v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover"
                                             :src="$page.props.user.profile_photo_url"
                                             :alt="$page.props.user.name"/>
                                    </button>

                                    <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Manage Account
                                    </div>

                                    <jet-dropdown-link :href="route('profile.show')">
                                        Profile
                                    </jet-dropdown-link>

                                    <jet-dropdown-link :href="route('api-tokens.index')"
                                                       v-if="$page.props.jetstream.hasApiFeatures">
                                        API Tokens
                                    </jet-dropdown-link>

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Authentication -->
                                    <form @submit.prevent="logout">
                                        <jet-dropdown-link as="button">
                                            Log Out
                                        </jet-dropdown-link>
                                    </form>
                                </template>
                            </jet-dropdown>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
                 class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <jet-responsive-nav-link :href="route('authenticated.dashboard')"
                                             :active="route().current('authenticated.dashboard')">
                        Dashboard
                    </jet-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover"
                                 :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name"/>
                        </div>

                        <div>
                            <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <jet-responsive-nav-link :href="route('profile.show')"
                                                 :active="route().current('profile.show')">
                            Profile
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link :href="route('api-tokens.index')"
                                                 :active="route().current('api-tokens.index')"
                                                 v-if="$page.props.jetstream.hasApiFeatures">
                            API Tokens
                        </jet-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" @submit.prevent="logout">
                            <jet-responsive-nav-link as="button">
                                Log Out
                            </jet-responsive-nav-link>
                        </form>

                        <!-- Team Management -->
                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                            <div class="border-t border-gray-200"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Team
                            </div>

                            <!-- Team Settings -->
                            <jet-responsive-nav-link :href="route('teams.show', $page.props.user.current_team)"
                                                     :active="route().current('teams.show')">
                                Team Settings
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link :href="route('teams.create')"
                                                     :active="route().current('teams.create')"
                                                     v-if="$page.props.jetstream.canCreateTeams">
                                Create New Team
                            </jet-responsive-nav-link>

                            <div class="border-t border-gray-200"></div>

                            <!-- Team Switcher -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Switch Teams
                            </div>

                            <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                <form @submit.prevent="switchToTeam(team)">
                                    <jet-responsive-nav-link as="button">
                                        <div class="flex items-center">
                                            <svg v-if="team.id == $page.props.user.current_team_id"
                                                 class="mr-2 h-5 w-5 text-green-400" fill="none"
                                                 stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>{{ team.name }}</div>
                                        </div>
                                    </jet-responsive-nav-link>
                                </form>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

<!--        content-->
        <div class="flex flex-1 overflow-hidden bg-[#FFE9FF]">
            <!--left sidebar-->
            <nav class="h-screen w-32 md:w-60 bg-gradient-to-b
                from-[#FF45FF] to-[#1B1B1D] rounded-tr-[2rem] px-2 py-4 md:px-10 md:py-16">
                <!-- Navigation Links -->
                <div class="space-y-8 gap-4">
                    <jet-nav-link :href="route('authenticated.dashboard')"
                                  :active="route().current('authenticated.dashboard')">
                        Dashboard
                    </jet-nav-link>
                </div>

                <div class="flex space-x-8 -my-px">
                    <jet-nav-link :href="route('authenticated.challenge-index')"
                                  :active="route().current('authenticated.challenge-index')">
                        Challenge
                    </jet-nav-link>
                </div>

                <div class="flex space-x-8 -my-px">
                    <jet-nav-link :href="route('authenticated.tracks-index')"
                                  :active="route().current('authenticated.tracks-index')">
                        Tracks
                    </jet-nav-link>
                </div>

                <div class="flex space-x-8 -my-px">
                    <jet-nav-link :href="route('authenticated.track-upload')"
                                  :active="route().current('authenticated.track-upload')">
                        Track Upload
                    </jet-nav-link>
                </div>
            </nav>

            <!--main-->
            <div class="flex flex-1 flex-col">
                <div class="w-full overflow-y-auto paragraph px-4 pb-24">
                    <!-- Page Heading -->
                    <header v-if="$slots.header">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <slot name="header"></slot>
                        </div>
                    </header>

                    <!--<star-rating id="star" :min="1" :max="10" v-model="stars"/>
                    <boxed-checkbox name="check" value="check1" v-model="checkbox">check1</boxed-checkbox>
                    <boxed-checkbox name="check" value="check2" v-model="checkbox"/>
                    <boxed-radio name="radio" value="radio1" v-model="radio"/>
                    <boxed-radio name="radio" value="radio2" v-model="radio"/>-->

                    <slot></slot>
                </div>
            </div>
            <!--right sidebar-->
            <div class="flex bg-[#FFCAFF] w-32 md:w-60 px-2 py-4 md:px-4 md:py-16 overflow-auto">
                <span>Istruzioni per l'utilizzo e la partecipazione ai contest</span>
            </div>
        </div>

        <!-- Bottom track controls -->
        <div class="fixed bg-clip-padding backdrop-filter backdrop-blur-xl bg-gradient-to-r from-gray-900 to-bg-gray-500
                rounded-tl-[2rem] bottom-0 left-0 right-0 ml-0 h-24">

        </div>
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

export default defineComponent({
    name: "AppLayoutDesktop",

    props: {
        title: String,
    },

    components: {
        BoxedRadio,
        BoxedCheckbox,
        StarRating,
        Head,
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: true,
            stars: 0,
            checkbox: false,
            radio: false,
        }
    },

    computed: {},

    methods: {
        switchToTeam(team) {
            this.$inertia.put(route('current-team.update'), {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },

        logout() {
            this.$inertia.post(route('logout'));
        },
    }
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
