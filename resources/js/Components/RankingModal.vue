<template>
  <div id="ranking-modal">
    <div class="ranking-modal__inner">
      <i @click="rankingModal" class="fa-solid fa-xmark"></i>
      <div
        class="type"
        v-for="(praise, index) in praiseTypes"
        :key="index"
        :id="praise.text"
        @click="postPraise(praise.text)"
      >
        <div class="bar">{{ numPraisesOfType[index] }}</div>
        <span v-html="praise.icon"></span>
        {{ praise.text }}
      </div>
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
      updateInterval: null,
    };
  },
  props: {
    praises: Array,
    charId: Number,
  },
  methods: {
    rankingModal: function () {
      clearInterval(this.updateInterval);
      this.$emit("rankingModal");
    },
    queryPraises: async function (interval) {
      const { data } = await axios
        .get(`/api/praise/${this.charId}`)
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
          clearInterval(interval);
        });
      this.$emit("praiseUpdate", data);
      this.updateBars();
    },
    updateBars: function () {
      const maxCategoryAmount = Math.max(...this.numPraisesOfType);
      const bars = document.querySelectorAll(".type > .bar");
      bars.forEach((bar) => {
        const amount = parseInt(bar.textContent);
        bar.style.height = (100 * amount) / maxCategoryAmount + "%";
        if (amount !== 0) {
          bar.classList.add("exists");
        } else {
          bar.classList.remove("exists");
        }
      });
    },
  },
  computed: {
    numPraisesOfType() {
      const self = this;
      let praiseCountArray = [];
      this.praiseTypes.forEach((praiseType) => {
        const numPraises = self.praises.filter(
          (praise) => praise.type === praiseType.text
        ).length;
        praiseCountArray.push(numPraises);
      });
      return praiseCountArray;
    },
  },
  mounted: function () {
    const self = this;
    this.updateBars();
    this.updateInterval = setInterval(() => {
      self.queryPraises(this.updateInterval);
    }, 5000);
  },
  destroyed: function () {
    clearInterval(this.updateInterval);
  },
};
</script>

<style>
#ranking-modal {
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
.ranking-modal__inner {
  position: absolute;
  width: 500px;
  height: 250px;
  padding: 20px;
  display: flex;
  gap: 20px;
  background-color: var(--primary-background-color);
  justify-content: center;
}

.ranking-modal__inner .fa-xmark {
  position: absolute;
  top: 5px;
  right: 5px;
  cursor: pointer;
  color: var(--primary-text-color);
}

.ranking-modal__inner .fa-xmark:hover {
  filter: brightness(0.5);
}

.ranking-modal__inner .type {
  flex: auto;
  color: var(--secondary-text-color);
  border: none;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  font-size: 1rem;
  font-weight: 800;
  gap: 10px;
  max-width: 70px;
  width: 100%;
}
.ranking-modal__inner .type i {
  font-size: 20px;
}
.type .bar {
  width: 100%;
  height: 30px;
  text-align: center;
  transition: 1s ease-in-out;
  min-height: 30px;
}
.type .bar.exists {
  background-color: var(--primary-color);
  padding-top: 10px;
}
</style>