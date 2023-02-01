<template>
    <form id="search-container" @submit.prevent="search()">
        <div @click="realmMenu()" id="fake-select">
            <span id="fake-select__label">{{ form.realm.name }}</span>
            <i class="fa-solid fa-chevron-down"></i>
            <div id="fake-select__dropdown" v-if="realmDropdownActive">
                <div v-if="realmFetching" class="loading">
                    <div class="fa-3x">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
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
        <input
            type="text"
            v-model="form.character"
            placeholder="Search characters..."
        />
        <button type="submit"><i class="fa-solid fa-search"></i></button>
        <div
            id="search-container__errors"
            :class="errors ? 'active' : ''"
        ></div>
    </form>

    <div v-if="Object.keys(results).length !== 0 || charFetching" id="results">
        <div v-if="charFetching" class="loading">
            <div class="fa-3x">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
        <CharacterCard
            v-if="Object.keys(results).length !== 0 && !charFetching"
            :region="region"
            :results="results"
        ></CharacterCard>
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
            realmFetching: false,
            charFetching: false,
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
            if (this.realmDropdownActive !== true) {
                this.realmDropdownActive = true;
            } else {
                this.realmDropdownActive = false;
            }

            if (this.realms.length === 0) {
                this.realmFetching = true;
                const res = await fetch("/api/realms", {
                    method: "GET",
                });
                this.realmFetching = false;
                const data = await res.json();
                data.sort((a, b) => a.name > b.name);
                this.realms = data;
            }
        },
        selectRealm: function (slug, name) {
            this.form.realm.slug = slug;
            this.form.realm.name = name;
        },
        search: async function () {
            this.charFetching = true;
            const res = await fetch(
                `/api/realms/${this.form.realm.name}/characters/${this.form.character}`,
                {
                    method: "GET",
                }
            );
            const data = await res.json();
            this.results = data;
            this.charFetching = false;
        },
    },
    mounted() {
        // Close dropdown if click occurs outside
        const self = this;
        window.addEventListener("click", (e) => {
            const fakeSelect = document.getElementById("fake-select");
            if (fakeSelect.contains(e.target)) return;
            if ((self.realmDropdownActive = true)) {
                self.realmDropdownActive = false;
            }
        });
        window.addEventListener("keydown", function (e) {
            const dropdown = document.getElementById("fake-select__dropdown");
            if (!self.realmDropdownActive) return;
            const key = e.key;
            for (let i = 0; i < dropdown.childNodes.length; i++) {
                if (
                    dropdown.childNodes[i].textContent
                        .charAt(0)
                        .toLowerCase() === key
                ) {
                    dropdown.childNodes[i].scrollIntoView();
                    break;
                }
            }
        });
    },
};
</script>
