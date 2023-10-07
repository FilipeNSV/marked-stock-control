<template>
  <div id="divDefault">
    <div class="text-start">
      <h3>Controle de Estoque</h3>
    </div>

    <!-- Alerts -->
    <Alerts v-if="alert" :alert="alert" :alertMsg="alertMsg" :alertStatus="alertStatus"/>

    <div class="card mt-3">
      <div class="card-body">
        
        <div class="table-responsive">
          <table class="table table-hover text-center">
            <thead>
              <tr>
                <th scope="col">Transação ID</th>
                <th scope="col">Protudo</th>
                <th scope="col">Status</th>
                <th scope="col">Data da Compra</th>
                <th scope="col">Data da Venda</th>
              </tr>
            </thead>
            <tbody v-if="paginatedStock.length > 0">
              <tr v-for="item in paginatedStock" :key="item.id">
                <td>{{item.transaction_id}}</td>
                <td>{{item.product_name}}</td>
                <td>{{item.status == 'available' ? 'Disponível' : 'Indisponível'}}</td>
                <td>{{formatarData(item.created_at)}}</td>
                <td>{{formatarData(item.deleted_at)}}</td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td v-if="loadingRequest"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
              </tr>
            </tbody>
          </table>
        </div>

        <Pagination v-if="listStock.length > 0" :list="listStock" @updatePaginatedItems="updatePaginatedStock"/>

      </div>
    </div>

  </div>
</template>

<script>
import { apiJson, apiMult } from "@/services/api"
import Alerts from "@/components/Alerts.vue"
import Pagination from "@/components/Pagination.vue"

export default {
  name: 'HomeView',
  data() {
    return {
      loadingRequest: false,
      alert: false,
      alertMsg: '',
      alertStatus: false,
      listStock: [],
      paginatedStock: [],
    }
  },
  components: {
    Alerts,
    Pagination
  },
  mounted () {
    this.getListStock()
  },
  methods: {
    getListStock () {
      apiJson.get('products-stock').then((response) => {
        this.listStock = response.data.data

      }).catch((error) => {
        console.log(error)
      })
    },
    updatePaginatedStock (newPaginatedItems) {
      this.paginatedStock = newPaginatedItems;
    },
    formatarData (data) {      
      if (data === '' || data === null || data === undefined) {
        return '-';
      }
      
      let partesDataHora = data.split(' ');
      let partesData = partesDataHora[0].split('-');
      let dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}`; // Formatação desejada
      return dataFormatada;      
    },
  }
}
</script>
