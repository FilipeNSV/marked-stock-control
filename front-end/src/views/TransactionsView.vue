<template>
  <div id="divDefault">
    <div class="text-start">
      <h3>
        Transações</h3>
    </div>

    <!-- Alerts -->
    <Alerts v-if="alert" :alert="alert" :alertMsg="alertMsg" :alertStatus="alertStatus"/>
    
    <button class="btn btn-primary mb-3" @click="divNewTransaction = !divNewTransaction">Nova transação</button>
    <!-- New Transaction -->
    <div class="card mb-3" v-if="divNewTransaction">
      <div class="card-body">
        <div>
          <div class="text-center">
            <h4>Novo Produto</h4>
          </div>
          <div class="text-end">
            <button class="btn btn-danger" @click="divNewTransaction = !divNewTransaction">Cancelar</button>
          </div>          
        </div>

        <form @submit.prevent="createTransaction()" class="row">
          <div class="col-md-2 mt-3">
            <label for="productType" class="form-label">Transação</label>
            <select class="form-select" v-model="formTransactionType" required>
              <option value="">Selecione</option>
              <option value="" disabled></option>
              <option value="purchase">Compra</option>
              <option value="sale">Venda</option>
            </select>
          </div>

          <div class="col-md-6 mt-3">
            <label v-if="formTransactionType == ''" class="form-label">Seleciona a Transação</label>
            <label v-if="formTransactionType == 'purchase'" for="transactionName" class="form-label">Nome do Fornecedor(opcional)</label>
            <label v-if="formTransactionType == 'sale'" for="transactionName" class="form-label">Nome do Cliente(opcional)</label>
            <input type="text" class="form-control" v-model="formTransactionName" id="transactionName" :disabled="formTransactionType == ''">
          </div>

          <div class="col-md-4 mt-3" v-if="formTransactionType == '' || formTransactionType == 'purchase'">
            <label class="form-label">Produto</label>
            <select class="form-select" v-model="formTransactionProduct" required :disabled="formTransactionType == ''">
              <option value="">Selecione</option>
              <option value="" disabled></option>
              <option v-for="item in listProducts" :value="item.id" :key="item.id">{{item.name}}</option>
              <option v-if="listProducts.length == 0" value="" disabled>Nenhum cadastrado</option>
            </select>
          </div>
          <div class="col-md-4 mt-3" v-else>
            <label class="form-label">Produto</label>
            <select class="form-select" v-model="formTransactionProduct" required :disabled="formTransactionType == ''">
              <option value="">Selecione</option>
              <option value="" disabled></option>
              <option v-for="item in listProductsForSale" :value="item.id" :key="item.id">{{item.name}}</option>
              <option v-if="listProductsForSale.length == 0" value="" disabled>Nenhum cadastrado</option>
            </select>
          </div>

          <div class="col-md-2 mt-3">
            <label class="form-label">Quantidade</label>
            <input type="number" class="form-control" min="1" v-model="formTransactionAmount" required :disabled="formTransactionProduct == ''">
          </div>

          <div class="col-md-3 mt-3" v-if="formTransactionType == 'purchase'">
            <label class="form-label">Valor sem Taxa</label>
            <input type="text" v-model="maskTransactionValue" class="form-control" disabled>
          </div>  

          <div class="col-md-3 mt-3" v-if="formTransactionType == 'purchase'">
            <label class="form-label">Taxa</label>
            <input type="text" v-model="maskTransactionTotalTax" class="form-control" disabled>
          </div>

          <div class="col-md-3 mt-3">
            <label class="form-label">Valor Total</label>
            <input type="text" v-model="maskTransactionTotalValue" class="form-control" disabled>
          </div>

          <div class="text-end mt-3">
            <button v-if="!loadingBtn" type="submit" class="btn btn-primary">Salvar</button>
            <button v-else class="btn btn-primary" type="button" disabled>
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...
            </button>           
          </div>      

        </form>
      </div>
    </div>
    <!-- End New Transaction -->  

    <!-- Transactions List -->
    <div class="card">
      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-hover text-center">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Transação</th>
                <th scope="col">Fornecedor/Cliente</th>
                <th scope="col">Produto</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody v-if="paginatedTransactions.length > 0">
              <tr v-for="item in paginatedTransactions" :key="item.id" typo="button" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#transactionDetail" title="Clique para mais detlhes do produto" @click="transactionDetail(item)">
                <td>{{item.id}}</td>
                <td>{{item.transaction_type == 'purchase' ? 'Compra' : 'Venda'}}</td>
                <td>{{item.supplier_name ? item.supplier_name : item.customer_name}}</td>
                <td>{{item.product_name}}</td>
                <td>{{formatValue1(item.total_value)}}</td>
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
              </tr>
            </tbody>
          </table>
        </div>

        <Pagination v-if="listTransactions.length > 0" :list="listTransactions" @updatePaginatedItems="updatePaginatedTransactions"/>
      </div>

      <!-- Modal Product Detail -->
      <div class="modal fade" id="transactionDetail" tabindex="-1" aria-labelledby="transactionDetailLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="transactionDetailLabel"><b>Transação ID: {{ modalTransactionData.id }}</b></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="clearTransactionModal()"></button>   
            </div>
            <div class="modal-body">
              <table class="table table-striped table-hover">            
                <tbody>
                  <tr><th scope="row">ID</th><td>{{modalTransactionData.id}}</td></tr>
                  <tr><th scope="row">Transação</th><td>{{modalTransactionData.transaction_type == 'purchase' ? 'Compra' : 'Venda'}}</td></tr>                
                  <tr><th scope="row">Fornecedor/Cliente</th><td>{{modalTransactionData.supplier_name ? modalTransactionData.supplier_name : modalTransactionData.customer_name}}</td></tr>                    
                  <tr><th scope="row">Produto</th><td>{{modalTransactionData.product_name}}</td></tr>
                  <tr><th scope="row">Quantidade</th><td>{{modalTransactionData.amount}}</td></tr>
                  <tr v-if="modalTransactionData.value_without_tax"><th scope="row">Valor sem Taxa</th><td>{{formatValue1(modalTransactionData.value_without_tax)}}</td></tr>
                  <tr v-if="modalTransactionData.total_tax"><th scope="row">Taxa</th><td>{{formatValue1(modalTransactionData.total_tax)}}</td></tr>
                  <tr><th scope="row">Valor Total</th><td>{{formatValue1(modalTransactionData.total_value)}}</td></tr>
                  <tr><th scope="row">Data</th><td>{{formatarData(modalTransactionData.created_at)}}</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Product Detail -->

    </div>
    <!-- End Transactions List -->

  </div>
</template>

<script>
import { apiJson, apiMult } from "@/services/api"
import Alerts from "@/components/Alerts.vue"
import Pagination from "@/components/Pagination.vue"

export default {
  name: 'TransactionsView',
  data() {
    return {
      loadingRequest: false,
      loadingBtn: false,
      alert: false,
      alertMsg: '',
      alertStatus: false,
      listTransactions: [],
      paginatedTransactions: [],
      listProducts: [],
      listProductsForSale: [],
      listProductTypes: [],
      divNewTransaction: false,
      formTransactionType: '',
      formTransactionProduct: '',
      formTransactionName: '',
      formTransactionAmount: 0,
      formTransactionValue: '',
      formTransactionTotalTax: '',
      formTransactionTotalValue: '',
      maskTransactionValue: '',
      maskTransactionTotalTax: '',
      maskTransactionTotalValue: '',
      modalTransactionData: []
    }
  },
  components: {
    Alerts,
    Pagination
  },
  mounted () {
    this.getListProducts()
    this.getListTransaction()
  },
  watch: {
    formTransactionAmount: function () {
      this.listProducts.forEach(item => {
        if (item.id == this.formTransactionProduct) {
          this.formTransactionValue = (item.value.replace(/\D/g, '') / 1000) * this.formTransactionAmount
          this.formTransactionTotalTax = (item.tax * 1) * this.formTransactionAmount
          this.formTransactionTotalValue = this.formTransactionValue + this.formTransactionTotalTax

          this.maskTransactionValue = this.formTransactionValue.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL',})
          this.maskTransactionTotalTax = this.formTransactionTotalTax.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL',})
          this.maskTransactionTotalValue = this.formTransactionTotalValue.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL',})
        }
      })
    },
    divNewTransaction: function () {
      if (!this.divNewTransaction) {
        this.formTransactionType = ''
        this.formTransactionProduct = ''
        this.formTransactionName = ''
        this.formTransactionAmount = 0
        this.formTransactionValue = ''
        this.formTransactionTotalTax = ''
        this.formTransactionTotalValue = ''
        this.maskTransactionValue = ''
        this.maskTransactionTotalTax = ''
        this.maskTransactionTotalValue = ''
      }
    }
  },
  methods: {
    getListTransaction () {
      this.listTransactions = []
      this.loadingRequest = true

      apiJson.get('transaction-list').then((response) => {
        this.listTransactions = response.data.data
        this.loadingRequest = false
      }).catch((error) => {
        if (error.response) {
          if(error.response.status == 404) {
            this.alertMsg = error.response.data.message
            this.alert = true
            this.alertStatus = false
            this.loadingBtn = false
          }
          console.log(error.response)
        } else {
          console.log(error)
        }
        this.loadingRequest = false
      })
    },
    getListProducts () {
      this.listProducts = []

      apiJson.get('products-list').then((response) => {
        this.listProducts = response.data.data
        this.getListStock()
      }).catch((error) => {
        console.log(error)
      })
    },    
    getListStock () {
      apiJson.get('products-stock').then((response) => {
        let stock = []
        let res = response.data.data
         
        res.forEach(item => {          
          if (!stock.includes(item.product_id) && item.status == 'available') {                     
            stock.push(item.product_id)
          }
        })

        this.listProducts.forEach(item => {
          if (stock.includes(item.id)) {
            this.listProductsForSale.push(item)
          }
        })

      }).catch((error) => {
        console.log(error)
      })
    },    
    getListProductsType () {
      this.listProductTypes = []

      apiJson.get('product-types-list').then((response) => {
        this.listProductTypes = response.data.data
      }).catch((error) => {        
        console.log(error)
      })
    },
    createTransaction () {
      let formData = new FormData()

      formData.append('transaction_type', this.formTransactionType)
      if (this.formTransactionType == 'purchase') {
        formData.append('supplier_name', this.formTransactionName)
        formData.append('value_without_tax', this.formTransactionValue)
        formData.append('total_tax', this.formTransactionTotalTax)
      } else {
        formData.append('customer_name', this.formTransactionName)
      }      
      formData.append('product_id', this.formTransactionProduct)
      formData.append('amount', this.formTransactionAmount)      
      formData.append('total_value', this.formTransactionTotalValue)

      apiJson.post('transaction-'+this.formTransactionType, formData).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }
        this.divNewTransaction = false

        setTimeout(() => {
          this.getListTransaction()
        }, 2000)   
      }).catch((error) => {
        if (error.response) {
          if(error.response.status == 404) {
            this.alertMsg = error.response.data.message
            this.alert = true
            this.alertStatus = false
            this.loadingBtn = false
          }
          console.log(error.response)
        } else {
          console.log(error)
        }
        this.loadingRequest = false
      })
    },
    updatePaginatedTransactions (newPaginatedItems) {
      this.paginatedTransactions = newPaginatedItems;
    },
    formatValue1 (value) {
      const numero = value * 1   

      // Formata o valor como dinheiro
      return value = numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
      })
    },
    transactionDetail (data) {
      this.modalTransactionData = data
    },
    clearTransactionModal () {
      this.modalTransactionData = []
    },
    formatarData (data) {      
      if (data === '' || data === null || data === undefined) {
        return '-';
      }
      
      let partesDataHora = data.split(' ');
      let partesData = partesDataHora[0].split('-');
      let dataFormatada = `${partesData[2]}/${partesData[1]}/${partesData[0]}`; // Formatação desejada
      return dataFormatada;      
    }
  }
}
</script>
