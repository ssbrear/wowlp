<template>
    <div id="headshot">
        <img :src="results.headshot" alt="Character headshot" />
    </div>
    <div id="info">
        <div id="individual">
            <div id="character_name">{{ results.name }}</div>
            <span>-</span>
            <div id="race">{{ results.race }}</div>
            <div id="class" :class="results.class.replace(/\s/g, '')">
                {{ results.class }}
            </div>
        </div>
        <div id="group">
            <div id="region">({{ region }})</div>
            <div id="realm_name">{{ results.realm }}</div>
            <div id="guild">&lt; {{ results.guild }} &gt;</div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        results: {
            name: String,
            guild: String,
            realm: String,
            race: String,
            class: String,
            headshot: String,
        },
        region: String,
    },
};
</script>
