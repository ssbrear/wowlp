<template>
  <div id="searchForm">
    <h1>WoW LP</h1>
    <form id="search-container" @submit.prevent="search()">
      <div @click="realmMenu()" id="fake-select">
        <span id="fake-select__label">{{ form.realm.name }}</span>
        <i class="fa-solid fa-chevron-down"></i>
        <div id="fake-select__dropdown" v-if="realmDropdownActive" :class="realmFetching ? 'loading' : ''">
          <div v-if="realmFetching" class="loading">
            <div class="fa-3x">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
          </div>
          <div
            v-for="realm in filteredRealms"
            :key="realm in filteredRealms"
            @click="selectRealm(realm.slug, realm.name)"
          >
            {{ realm.name }}
          </div>
        </div>
        <input
          autocomplete="off"
          type="text"
          v-if="realmDropdownActive"
          id="fake-select__search"
          v-model="realmSearch"
          tabindex="1"
        />
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
      <div id="search-container__inner">
        <input
          id="search"
          type="text"
          v-model="form.character"
          placeholder="Search characters..."
        />
        <button type="submit"><i class="fa-solid fa-search"></i></button>
      </div>
      <div id="search-container__errors" :class="errors ? 'active' : ''">
        Something went wrong, please try again.
      </div>
    </form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      realmFetching: false,
      charFetching: false,
      realms: [],
      filteredRealms: [],
      realmSearch: "",
      form: {
        realm: {
          slug: "realms",
          name: "Realms",
        },
        character: "",
      },
      realmDropdownActive: false,
      errors: false,
    };
  },
  props: {
    battletag: String,
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
        const { data } = await axios.get("/api/realms");
        this.realmFetching = false;
        this.realms = data;
        this.filteredRealms = data;
      }
    },
    selectRealm: function (slug, name) {
      this.form.realm.slug = slug;
      this.form.realm.name = name;
    },
    search: async function () {
      this.$emit("charFetching");
      this.charFetching = true;
      const { data } = await axios
        .get(
          `/api/realms/${this.form.realm.name}/characters/${this.form.character}`,
          { params: { praiser_id: this.battletag } }
        )
        .catch((error) => {
          if (error.response) {
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log("Error: ", error.message);
          }
          console.log(error.config);
          this.errors = true;
          const self = this;
          setTimeout(() => {
            self.errors = false;
          }, 5000);
        });
      this.charFetching = false;
      this.$emit("charData", data);
    },
    filterRealms: function (newSearch) {
      const realmSearch = newSearch.toLowerCase();
      const realms = this.realms;
      this.filteredRealms = realms.filter((realm) =>
        realm.name.toLowerCase().includes(realmSearch)
      );
    },
  },
  watch: {
    realmSearch: function (newSearch) {
      this.filterRealms(newSearch);
    },
  },
  mounted() {
    // Close dropdown if click occurs outside
    const self = this;
    window.addEventListener("click", (e) => {
      const searchContainer = document.getElementById("search-container");
      if (searchContainer.contains(e.target)) {
        if (document.activeElement.id !== "search") {
          document.querySelector("#fake-select__search").focus();
        }
        return;
      }
      if ((self.realmDropdownActive = true)) {
        self.realmDropdownActive = false;
      }
    });
  },
};
</script>

<style>
#searchForm {
  display: flex;
  justify-content: center;
  position: relative;
  z-index: 1;
  padding: 20px;
  flex-wrap: wrap;
  gap: 10px;
}
h1 {
  font-size: 4rem;
  text-align: center;
  color: var(--primary-text-color);
  flex-basis: 100%;
}
#search-container {
  display: flex;
  position: relative;
  height: 45px;
  width: 100%;
}

#search-container select {
  text-indent: 1px;
  text-overflow: "";
  display: none;
}

#fake-select {
  border-radius: 0;
  border: none;
  width: 275px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background-color: var(--tertiary-background-color);
  color: var(--secondary-text-color);
  font-weight: 600;
  cursor: pointer;
  font-size: 1rem;
  position: relative;
}

#fake-select__dropdown {
  position: absolute;
  background-color: var(--tertiary-background-color);
  max-height: 400px;
  top: 100%;
  left: 0;
  overflow-y: auto;
  width: 100%;
}
#fake-select__dropdown.loading {
  min-height: 100px;
}
#fake-select__dropdown .loading {
  min-height: 100px;
  transition: none;
  padding: 0;
}

#fake-select__search {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: var(--tertiary-background-color);
  color: var(--primary-text-color);
  border-bottom: 1px solid var(--secondary-text-color);
}

#fake-select__dropdown > div {
  padding: 5px 20px;
  transition: 0.3s;
}

#fake-select__dropdown div:hover {
  background-color: #bbbbbb;
}

#search-container__inner {
  position: relative;
  width: 100%;
}

input {
  outline: none;
  border: none;
  padding-right: 40px;
  padding-left: 20px;
  font-size: 1.25rem;
  font-family: inherit;
  font-weight: 500;
  height: 100%;
  width: 100%;
}

#search-container button {
  position: absolute;
  right: 0;
  top: calc(50% - 1px);
  transform: translateY(-50%);
  height: 100%;
  background: transparent;
  border: none;
  width: 40px;
  transition: 0.3s;
}

#search-container button:hover {
  background-color: var(--secondary-background-color);
}

#search-container__errors {
  position: absolute;
  width: 100%;
  min-height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: rgb(255, 100, 100);
  color: black;
  top: 0;
  padding: 10px;
  font-weight: bold;
  text-align: center;
  transition: 0.5s top ease-in-out;
  z-index: -1;
}

#search-container__errors.active {
  top: 100%;
}

#search-container {
  flex-direction: column;
  height: 90px;
  max-width: 500px;
}
#search-container > * {
  height: 50%;
  width: 100%;
}
#fake-select {
  order: 1;
}

</style>