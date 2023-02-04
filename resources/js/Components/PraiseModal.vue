<template>
  <div id="praise-modal">
    <div class="praise-modal__inner">
      <i @click="praiseModal" class="fa-solid fa-xmark"></i>
      <button
        class="option"
        v-for="(praise, index) in praiseTypes"
        :key="index"
        :id="praise.text"
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
  methods: {
    praiseModal: function() {
        this.$emit("praiseModal");
    }
  }
};
</script>
