<template>
  <div id="character-card">
    <div v-if="charFetching" class="loading">
      <div class="fa-3x">
        <i class="fas fa-spinner fa-spin"></i>
      </div>
    </div>
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
        <div id="region">(US)</div>
        <div id="realm_name">{{ results.realm }}</div>
        <div id="guild">&lt; {{ results.guild }} &gt;</div>
      </div>
      <div id="links">
        <a target="_blank" :href="armoryLink" id="armory">Armory</a>
        <a target="_blank" :href="raiderioLink" id="raiderio">Raider.io</a>
        <a target="_blank" :href="warcraftlogsLink" id="warcraftlogs">Logs</a>
      </div>
    </div>
    <div v-if="battletag" id="actions">
      <button v-if="!results.praised" @click="praiseModal">
        <div class="fa-3x">
          <i class="fa-regular fa-heart"></i>
        </div>
      </button>
      <button v-if="results.praised" @click="removePraise">
        <div class="fa-3x">
          <i class="fa-solid fa-heart"></i>
        </div>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      armoryLink:
        "https://worldofwarcraft.com/en-us/character/us/" +
        this.results.realm +
        "/" +
        this.results.name,
      raiderioLink:
        "https://raider.io/characters/us/" +
        this.results.realm.toLowerCase() +
        "/" +
        this.results.name,
      warcraftlogsLink:
        "https://www.warcraftlogs.com/character/us/" +
        this.results.realm.toLowerCase() +
        "/" +
        this.results.name,
    };
  },
  props: {
    results: {
      name: String,
      guild: String,
      realm: String,
      race: String,
      class: String,
      headshot: String,
      prasied: Boolean,
    },
    charFetching: Boolean,
    battletag: String,
  },
  methods: {
    praiseModal() {
      this.$emit("praiseModal");
    },
    async removePraise() {
      const { data } = await axios.delete("/api/praise", {
        params: {
          praiser_id: this.battletag,
          character_name_realm_name: `${this.results.name}_${this.results.realm}`,
        },
      });
      if (data.status === "200") {
        this.$emit('praiseState', false);
      }
    },
  },
  watch: {
    charFetching: function (newVal, oldVal) {
      if (newVal === true) this.results.headshot = "";
    },
  },
};
</script>

<style>
#character-card {
  display: flex;
  background-color: var(--secondary-background-color);
  color: var(--secondary-text-color);
  padding: 2em;
  margin: 4em auto 0;
  max-width: 650px;
  position: relative;
}
#headshot {
  width: 84px;
  height: 84px;
  margin-right: 20px;
}

#info {
  display: flex;
  flex-direction: column;
  gap: 10px;
  font-weight: 500;
}

#info > div {
  display: flex;
  gap: 10px;
}

a {
  text-decoration: none;
  transition: 0.3s;
  font-size: 14px;
  font-weight: 700;
}

#actions {
  display: flex;
  align-items: center;
  margin-left: auto;
}

#actions button {
  outline: none;
  background-color: transparent;
  border: none;
  cursor: pointer;
}

#actions i {
  color: var(--primary-color);
  transition: 0.3s;
}

#actions i:hover {
  color: var(--primary-color-highlight);
}
.loading {
  background-color: var(--secondary-background-color);
}
</style>