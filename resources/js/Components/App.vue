<template>
  <label for="themeToggle" class="switchContainer">
    <input id="themeToggle" type="checkbox" />
    <span class="switch"></span>
  </label>
  <CharLookup />
</template>
  
  <script>
import CharLookup from "./CharLookup.vue";
export default {
  components: {
    CharLookup: CharLookup,
  },
};
</script>
  
<style>
#app {
    font-family: Cinzel;
    height: 100%;
}
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
</style>