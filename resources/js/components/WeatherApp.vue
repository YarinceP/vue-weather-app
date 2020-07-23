<template>
    <div class="text-white mb-8">
        <div class="places-input text-gray-800" >
            <input type="text" class="w-full">
        </div>
        <div class="weather-container font-sans w-128 max-w-lg overflow-hidden bg-gray-900 shadow-lg mt-4">

        <div class="current-weather flex items-center justify-between px-6 py-8">
            <div>One</div>
            <div>Two</div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
