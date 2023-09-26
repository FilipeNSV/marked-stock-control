<template>
  <div id="app">
    <SideBar/>
    <!-- <router-view/> -->
  </div>
</template>

<script>
import SideBar from './components/SideBar.vue'

export default {
  name: 'App',
  data() {
    return {}
  },
  components: {
    SideBar
  },
}
</script>

<style>
#app {
  background-color: whitesmoke;
}

#sideBar {
  border-right: 1px solid gray;
  box-shadow: 5px 0px 10px rgba(0, 0, 0, 0.2);
  min-width: 60px;
  width: 280px;
  flex: 0 0 280px; /* Largura fixa do SideBar */
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  background-color: #f8f9fa; /* Cor de fundo do SideBar */
  overflow-y: auto; /* Adicione rolagem se o conteúdo for maior que a altura */
  z-index: 1; /* Para que o SideBar esteja acima do conteúdo */
}

@media (max-width: 768px) {
  #sideBar .d-md-inline {
    display: none;
  }
  #sideBar {
    width: 60px;
  }
}

#divDefault {
  margin-left: 20px;
  margin-right: 20px;
  margin-top: 20px;
}

#contentWrapper {
  flex: 1; /* O conteúdo ocupa o restante do espaço disponível */
  padding-left: 280px; /* Deixa espaço para o SideBar */
  background-color: #ffffff; /* Cor de fundo do conteúdo */
  position: relative; /* Para que o conteúdo não sobreponha o SideBar */
}

@media (max-width: 768px) {
  #divDefault {
    margin-left: 60px;
  }

  #contentWrapper {
    padding-left: 60px;
  }
}

.card {
  box-shadow: 5px 0px 10px rgba(0, 0, 0, 0.2);
}
</style>
