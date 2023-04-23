<template>
  <div id="praise-modal">
    <div class="praise-modal__inner">
      <i @click="praiseModal" class="fa-solid fa-xmark"></i>
      <button
        class="option"
        v-for="(praise, index) in praiseTypes"
        :key="index"
        :id="praise.text"
        @click="postPraise(praise.text)"
      >
        <span class="fa-3x" v-html="praise.icon"></span>
        {{ praise.text }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      praiseTypes: [
        {
          text: "Pumper",
          icon: '<i class="fa-solid fa-dumbbell"></i>',
        },
        {
          text: "Technical",
          icon: '<i class="fa-solid fa-gears"></i>',
        },
        {
          text: "Leader",
          icon: '<i class="fa-solid fa-crown"></i>',
        },
        {
          text: "Helpful",
          icon: '<i class="fa-solid fa-hands-holding-child"></i>',
        },
        {
          text: "Attitude",
          icon: '<i class="fa-solid fa-face-laugh-beam"></i>',
        },
      ],
    };
  },
  props: {
    battletag: String,
    results: {
      name: String,
      guild: String,
      realm: String,
      race: String,
      class: String,
      headshot: String,
      praises: Array,
    },
  },
  methods: {
    praiseModal: function () {
      this.$emit("praiseModal");
    },
    postPraise: async function (praise) {
      const res = await axios.post("/api/praise", {
        praiser_id: this.battletag,
        character_name_realm_name: `${this.results.name}_${this.results.realm}`,
        type: praise,
      });
      this.praiseModal();
      this.$emit('praiseState', true);
    },
  },
};
</script>

<style>
#praise-modal {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 100vw;
  z-index: 1;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}
.praise-modal__inner {
  position: absolute;
  width: 850px;
  height: 250px;
  padding: 20px;
  display: flex;
  gap: 20px;
  background-color: var(--primary-background-color);
}

.praise-modal__inner .fa-xmark {
  position: absolute;
  top: 5px;
  right: 5px;
  cursor: pointer;
  color: var(--primary-text-color);
}

.praise-modal__inner .fa-xmark:hover {
  filter: brightness(0.5);
}

.praise-modal__inner .option {
  flex: auto;
  background-color: var(--secondary-background-color);
  color: var(--secondary-text-color);
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: center;
  font-size: 1rem;
  font-weight: 800;
  gap: 20px;
}

.praise-modal__inner .option:hover {
  filter: brightness(0.8);
}

.praise-modal__inner .option:active {
  filter: brightness(0.7);
}
</style>