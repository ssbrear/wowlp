<template>
  <div id="char-lookup">
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
    <a v-if="!battletag" :href="loginLink" id="bnetLogin"
      ><i class="fab fa-battle-net"></i> Login with Battle.net</a
    >
  </div>
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

      loginLink:
        "https://us.battle.net/oauth/authorize?client_id=ffc047e876f54c678c3a554f461fa617&redirect_uri=" +
        window.location.protocol +
        "//" +
        window.location.host +
        "/redirect&response_type=code&scope=openid&state=",
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
      if (!battletag.includes("#")) return;
      const [battletagString, battletagNum] = battletag.split("#");
      const { data } = await axios
        .get(
          `/api/battletag-str/${battletagString}/battletag-num/${battletagNum}`
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
        });
      this.battletag = data;
    }
  },
};
</script>

<style>
#char-lookup {
  width: min(100%, 350px);
  background-color: var(--secondary-background-color);
  height: 100%;
  display: flex;
  flex-direction: column;
}
h3 {
  user-select: none;
  text-align: center;
  height: 22px;
  font-size: 18px;
  color: var(--primary-text-color);
}
h3 a {
  font-size: inherit;
  text-shadow: black 0 0 5px;
}
#bnetLogin {
  margin-top: auto;
  padding: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  background-color: #185894;
  color: white;
  font-size: 1.2rem;
}
#bnetLogin i { 
  font-size: 1.75rem;
}
@media (max-width: 820px) {
  h1 {
    font-size: 40px;
  }
}
</style>