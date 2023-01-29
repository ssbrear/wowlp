<template>
    <h1>WoW LP</h1>
    <form
        id="search-form"
        onsubmit="return false"
        method="GET"
        action="{{ route('characters.get') }}"
    >
        <select></select>
        <input type="text" />
        <button type="submit"><i class="fa-solid fa-search"></i></button>
    </form>
    <div id="results" v-if="!fetching">
        <div class="fa-3x">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            fetching: true,
        };
    },
    mounted: async function () {
        const RealmIndexURL =
            "https://us.api.blizzard.com/data/wow/realm/index?namespace=dynamic-us&locale=en_US";

    },
};
</script>
