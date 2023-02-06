<template>
  <a
    href="https://us.battle.net/oauth/authorize?client_id=ffc047e876f54c678c3a554f461fa617&redirect_uri=http://localhost:8000/api/battlenet/callback&response_type=code&scope=openid&state="
    id="bnetLogin"
    >Login with Battle.net</a
  >
  <label for="themeToggle" class="switchContainer">
    <input id="themeToggle" type="checkbox" />
    <span class="switch"></span>
  </label>
  <h1>WoW LP</h1>
  <SearchForm
    @char-data="charDataListener"
    @char-fetching="charFetchingListener"
  ></SearchForm>
  <CharacterCard
    @praise-modal="praiseModalListener"
    v-if="Object.keys(results).length !== 0"
    :results="results"
    :charFetching="charFetching"
  ></CharacterCard>
  <PraiseModal @praise-modal="praiseModalListener" v-if="praiseModal">
  </PraiseModal>
</template>

<script>
import SearchForm from "./SearchForm.vue";
import CharacterCard from "./CharacterCard.vue";
import PraiseModal from "./PraiseModal.vue";
export default {
  data() {
    return {
      praiseModal: false,
      charFetching: false,
      results: {},
      authCode: "",
      token: "",
    };
  },
  components: {
    SearchForm: SearchForm,
    CharacterCard: CharacterCard,
    PraiseModal: PraiseModal,
  },
  methods: {
    praiseModalListener: function () {
      this.praiseModal = !this.praiseModal;
    },
    charDataListener: function (data) {
      this.results = data;
      this.charFetching = false;
    },
    charFetchingListener: function () {
      this.charFetching = true;
    },
  },
  created: async function () {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("code")) {
      this.authCode = urlParams.get("code");
      const res = await fetch(`/api/battlenet/auth/${this.authCode}`, {
        method: "GET",
      });
      const data = await res.json();
      this.token = data;
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
  color: var(--primary-text-color);
  text-align: center;
}
</style>