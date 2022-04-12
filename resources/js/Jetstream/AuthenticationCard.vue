<template>
    <div class="h-screen w-screen grid sm:grid-cols-3 login_back bg-no-repeat bg-cover">
        <div class="hidden md:block p-10">
            <span class="text-left text-gray-100 font-semibold text-2xl">
                Find news music, win just by listening to music and voting
            </span>
        </div>
        <div class="col-span-2 md:col-start-2 shadow-md glass overflow-hidden md:border-gray-500 md:border-l-4
                md:rounded-l-[3rem] py-20 md:p-20">
            <div class="flex justify-center items-center">
                <slot name="logo"/>
            </div>

            <div class="flex justify-center items-center">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden">
                    <slot/>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
.login_back {
    background-image: url('../../assets/images/login_back.png');
}
</style>
