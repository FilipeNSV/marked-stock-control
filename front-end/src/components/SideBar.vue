<template>
  <div id="app" class="d-flex">
    <div id="sideBar" class="d-flex flex-column p-3 bg-body-tertiary">
      <div class="text-center fs-5">
        <i class="bi bi-shop"></i>
        <span class="fs-5 d-none d-md-inline">&nbsp;Sistema</span>
      </div>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <router-link to="/" class="nav-link text-dark">
            <i class="bi bi-house-fill"></i>
            <span class="d-none d-md-inline">&nbsp;Home</span>
          </router-link>
        </li>
        <li>
          <router-link to="/produtos" class="nav-link text-dark">
            <i class="bi bi-box-seam-fill"></i>
            <span class="d-none d-md-inline">&nbsp;Produtos</span>
          </router-link>
        </li>
        <li>
          <router-link to="/transacoes" class="nav-link text-dark">
            <i class="bi bi-cash"></i>
            <span class="d-none d-md-inline">&nbsp;Transações</span>
          </router-link>
        </li>
      </ul>
      <hr>
      <div class="text-center">
        <span class="text-dark">© 2023 Filipe NSV Dev</span>
      </div>
    </div>

    <div id="contentWrapper">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  name: 'SideBar'
}
</script>
