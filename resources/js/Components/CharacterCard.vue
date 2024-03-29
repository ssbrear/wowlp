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
        <span class="delimeter">-</span>
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
    <div id="ranking">
      <button @click="rankingModal">
        <i class="fa-solid fa-ranking-star"></i>
        LP
      </button>
    </div>
    <div v-if="battletag" id="actions">
      <button aria-label="praise" v-if="!results.praised" @click="praiseModal">
        <div class="fa-2x">
          <i class="fa-regular fa-heart"></i>
        </div>
      </button>
      <button
        aria-label="remove praise"
        v-if="results.praised"
        @click="removePraise"
      >
        <div class="fa-2x">
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
    rankingModal() {
      this.$emit("rankingModal");
    },
    async removePraise() {
      const { data } = await axios.delete("/api/praise", {
        params: {
          praiser_id: this.battletag,
          character_name_realm_name: `${this.results.name}_${this.results.realm}`,
        },
      });
      if (data.status === "200") {
        this.$emit("praiseState", false);
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

#ranking {
  margin-left: auto;
  display: flex;
}
#ranking button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 20px;
  color: var(--primary-text-color);
}
#ranking i {
  color: var(--primary-color);
}

#actions {
  display: flex;
  align-items: center;
  position: absolute;
  top: 5px;
  right: 5px;
}

#actions button {
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
@media (max-width: 820px) {
  #character-card {
    padding: 10px;
    max-width: 400px;
    margin-top: 6em;
  }
  #headshot {
    position: absolute;
    height: 90px;
    width: 90px;
    top: -50%;
    left: 50%;
    transform: translate(-50%, 50%);
    border-radius: 50%;
    border: 5px solid var(--secondary-background-color);
  }
  #headshot img {
    border-radius: 50%;
  }
  #info * {
    font-size: 14px;
  }
  #individual {
    display: flex;
    flex-direction: column;
  }
  #group {
    display: flex;
    flex-wrap: wrap;
  }
  #guild {
    font-size: 16px;
    flex: 100%;
  }
  .delimeter {
    display: none;
  }
  #ranking button {
    font-size: 16px;
  }
  #actions {
    top: 0;
  }
  #actions i {
    font-size: 20px;
  }
}
</style>