<template>
  <a
    v-if="!battletag"
    href="https://us.battle.net/oauth/authorize?client_id=ffc047e876f54c678c3a554f461fa617&redirect_uri=http://localhost:8000/redirect&response_type=code&scope=openid&state="
    id="bnetLogin"
    >Login with Battle.net</a
  >
  <label for="themeToggle" class="switchContainer">
    <input id="themeToggle" type="checkbox" />
    <span class="switch"></span>
  </label>
  <h1>WoW LP</h1>
  <h4>{{ battletag }}</h4>
  <SearchForm
    :battletag="battletag"
    @char-data="charDataListener"
    @char-fetching="charFetchingListener"
  ></SearchForm>
  <CharacterCard
    :battletag="battletag"
    :results="results"
    :charFetching="charFetching"
    @praise-modal="praiseModalListener"
    @praise-state="praiseStateListener"
    @ranking-modal="rankingModalListener"
    v-if="Object.keys(results).length !== 0"
  ></CharacterCard>
  <PraiseModal
    :battletag="battletag"
    :results="results"
    @praise-modal="praiseModalListener"
    @praise-state="praiseStateListener"
    v-if="praiseModal"
  >
  </PraiseModal>
  <RankingModal
    :praises="results.praises"
    :charId="results.id"
    @ranking-modal="rankingModalListener"
    @praiseUpdate="praiseUpdateListener"
    v-if="rankingModal"
  >
  </RankingModal>
</template>

<script>
import SearchForm from "./SearchForm.vue";
import CharacterCard from "./CharacterCard.vue";
import PraiseModal from "./PraiseModal.vue";
import RankingModal from "./RankingModal.vue";

export default {
  data() {
    return {
      praiseModal: false,
      rankingModal: false,
      charFetching: false,
      results: {},
      authCode: "",
      battletag: null,
    };
  },
  components: {
    SearchForm: SearchForm,
    CharacterCard: CharacterCard,
    PraiseModal: PraiseModal,
    RankingModal: RankingModal,
  },
  methods: {
    praiseModalListener: function () {
      this.praiseModal = !this.praiseModal;
    },
    rankingModalListener: function () {
      this.rankingModal = !this.rankingModal;
    },
    praiseStateListener: function (state) {
      this.results.praised = state;
    },
    charDataListener: function (data) {
      this.results = data;
      this.charFetching = false;
    },
    charFetchingListener: function () {
      this.charFetching = true;
    },
    praiseUpdateListener: function (data) {
      this.results.praises = data;
    },
  },
  created: async function () {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("battletag")) {
      const battletag = urlParams.get("battletag");
      const [battletagString, battletagNum] = battletag.split("#");
      const { data } = await axios
        .get(`/api/battletag-str/${battletagString}/battletag-num/${battletagNum}`)
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
        });
      this.battletag = data;
    }
  },
};
</script>

<style>
.switchContainer {
  position: fixed;
  top: 10px;
  right: 10px;
}

.switchContainer input[type="checkbox"] {
  display: none !important;
}

.switchContainer .switch {
  display: block;
  width: 50px;
  height: 20px;
  border-radius: 50px;
  background-color: var(--primary-text-color);
  cursor: pointer;
  transition: 0.3s;
}

.switchContainer .switch::before {
  content: "";
  position: absolute;
  left: 2.5%;
  top: 5%;
  width: 40%;
  height: 90%;
  border-radius: 50px;
  transition: 0.3s;
  background-color: var(--primary-background-color);
}

.switchContainer input[type="checkbox"]:checked + .switch::before {
  left: 57.5%;
}

h1 {
  font-size: 4rem;
  user-select: none;
  color: #121212;
  text-align: center;
}
h4 {
  user-select: none;
  color: #121212;
  text-align: center;
  height: 22px;
}
</style>