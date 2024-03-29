<template>
  <div id="searchForm">
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
      <div id="search-container__inner">
        <input
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
        data.sort((a, b) => a.name > b.name);
        this.realms = data;
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
          dropdown.childNodes[i].textContent.charAt(0).toLowerCase() === key
        ) {
          dropdown.childNodes[i].scrollIntoView();
          break;
        }
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
  max-width: 587px;
  margin: auto;
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
  width: 240px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background-color: var(--secondary-background-color);
  color: var(--secondary-text-color);
  font-weight: 600;
  cursor: pointer;
  font-size: 1rem;
  position: relative;
}

#fake-select__dropdown {
  position: absolute;
  flex-direction: column;
  background-color: var(--secondary-background-color);
  height: min(100vh, 400px);
  top: 100%;
  left: 0;
  overflow-y: scroll;
  width: 100%;
}

#fake-select__dropdown div {
  padding: 5px 20px;
  transition: 0.3s;
}

#fake-select__dropdown div:hover {
  background-color: #bbbbbb;
}

#search-container__inner {
  position: relative;
}

#search-container input {
  border: none;
  padding-right: 40px;
  padding-left: 20px;
  font-family: inherit;
  font-weight: 500;
  font-size: 1.25rem;
  height: 100%;
  width: 100%;
}

#search-container input:focus-visible {
  outline: none;
}

#search-container button {
  position: absolute;
  right: 0;
  top: 50%;
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

@media (max-width: 820px) {
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
}
</style>