<template>
    <form id="search-container" @submit.prevent="search()">
        <div @click="realmMenu()" id="fake-select">
            <span id="fake-select__label">{{ form.realm.name }}</span>
            <i class="fa-solid fa-chevron-down"></i>
            <div id="fake-select__dropdown" v-if="realmDropdownActive">
                <div
                    v-for="realm in realms"
                    :key="realm in realms"
                    @click="selectRealm(realm.slug, realm.name)"
                >
                    {{ realm.name }}
                </div>
            </div>
        </div>
        <select id="realm-select" v-model="form.realm">
            <option class="hidden" value="realms"></option>
            <option
                v-for="realm in realms"
                :key="realm in realms"
                :value="realm.slug"
            >
                {{ realm.name }}
            </option>
        </select>
        <input type="text" v-model="form.character" />
        <button type="submit"><i class="fa-solid fa-search"></i></button>
        <div
            id="search-container__errors"
            :class="errors ? 'active' : ''"
        ></div>
    </form>

    <div v-if="Object.keys(results).length !== 0" id="results">
        <div v-if="fetching" class="loading">
            <div class="fa-3x">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
        <CharacterCard :region="region" :results="results"></CharacterCard>
    </div>
</template>
<script>
import CharacterCard from "./CharacterCard.vue";
export default {
    components: {
        CharacterCard: CharacterCard,
    },
    data() {
        return {
            fetching: false,
            realms: [],
            form: {
                realm: {
                    slug: "realms",
                    name: "Realms",
                },
                character: "",
            },
            realmDropdownActive: false,
            errors: false,
            results: {},
            region: "US",
        };
    },
    methods: {
        realmMenu: async function () {
            if (this.realms.length === 0) {
                this.fetching = true;
                const res = await fetch("/api/realms", {
                    method: "GET",
                });
                this.fetching = false;
                const data = await res.json();
                data.sort((a, b) => a.name > b.name);
                this.realms = data;
            }
            if (this.realmDropdownActive !== true) {
                this.realmDropdownActive = true;
            } else {
                this.realmDropdownActive = false;
            }
        },
        selectRealm: function (slug, name) {
            this.form.realm.slug = slug;
            this.form.realm.name = name;
        },
        search: async function () {
            this.fetching = true;
            const res = await fetch(
                `/api/realms/${this.form.realm.name}/characters/${this.form.character}`,
                {
                    method: "GET",
                }
            );
            const data = await res.json();
            this.results = data;
            this.fetching = false;
        },
    },
    mounted() {
        // Close dropdown if click occurs outside
        const self = this;
        const fakeSelect = document.getElementById("fake-select");
        window.addEventListener("click", (e) => {
            if (fakeSelect.contains(e.target)) return;
            if ((self.realmDropdownActive = true)) {
                self.realmDropdownActive = false;
            }
        });
    },
};
</script>
