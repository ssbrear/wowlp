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
    <div id="links">
      <a target="_blank" :href="armoryLink" id="armory">Armory</a>
      <a target="_blank" :href="raiderioLink" id="raiderio">Raider.io</a>
      <a target="_blank" :href="warcraftlogsLink" id="warcraftlogs">Logs</a>
    </div>
  </div>
  <div id="actions">
    <button @click="praiseModal">
      <div class="fa-3x">
        <i class="fa-regular fa-heart"></i>
      </div>
    </button>
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
        "https://www.warcraftlogs.com/character/us/" +
        this.results.realm.toLowerCase() +
        "/" +
        this.results.name,
      warcraftlogsLink:
        "https://raider.io/characters/us/" +
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
    },
    region: String,
  },
  methods: {
    praiseModal() {
      this.$emit("praiseModal");
    },
  },
};
</script>
