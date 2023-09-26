<template>
  <div id="divDefault">
    <div class="text-start">
      <h3>Produtos</h3>
    </div>

    <!-- Alerts -->
    <Alerts v-if="alert" :alert="alert" :alertMsg="alertMsg" :alertStatus="alertStatus"/>    

    <!-- Products -->
    <div class="card">
      <div class="card-body">

        <!-- New Product -->
        <div>
          <div v-if="!divNewProduct" style="margin-bottom: 10px;">
            <button class="btn btn-primary" @click="divNewProduct = !divNewProduct">Novo produto</button>
          </div>
          <div v-if="divNewProduct">
            <div class="text-center">
              <h4>Novo Produto</h4>
            </div>
            <div class="text-end">
              <button class="btn btn-danger" @click="divNewProduct = !divNewProduct">Cancelar</button>
            </div>          
          </div>
          
          <form @submit.prevent="createProduct()" class="row" v-if="divNewProduct">
            <div class="col-md-6">
              <label for="productName" class="form-label">Nome</label>
              <input type="text" class="form-control" v-model="formProductName" id="productName" placeholder="Nome do produto" required>
            </div>

            <div class="col-md-6">
              <label for="productType" class="form-label">Tipo</label>
              <select class="form-select" v-model="formProductType" required>
                <option value="">Selecione</option>
                <option value="" disabled></option>
                <option v-for="item in listProductTypes" :value="item.id" :key="item.id">{{item.name}}</option>
                <option  v-if="listProductTypes.length == 0" value="" disabled>Nenhum cadastrado</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="productDescription" class="form-label">Descrição</label>
              <textarea type="text" class="form-control" v-model="formProductDescription" id="productDescription" cols="30" rows="2" placeholder="Nome do produto" required></textarea>
            </div>

            <div class="col-md-6">
              <label for="productValue" class="form-label">Valor</label>            
              <input type="text" :value="maskValue" @input="formatValue($event.target.value)" class="form-control" id="productValue" placeholder="10,50" required>            
            </div>

            <div class="text-end">
              <button v-if="!loadingBtn" type="submit" class="btn btn-primary">Salvar</button>
              <button v-else class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...
              </button>           
            </div><br><br>
            <hr>       

          </form>
        </div>
        <!-- End New Product -->
        
        <!-- Products List -->
        <h5 class="card-title text-center">Lista de Produtos</h5>
        <div class="table-responsive">
          <table class="table table-hover text-center">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Tipo</th>
                <th scope="col">Valor</th>
              </tr>
            </thead>
            <tbody v-if="paginatedProducts.length > 0">
              <tr v-for="item in paginatedProducts" :key="item.id" typo="button" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#productDetail" title="Clique para mais detlhes do produto" @click="productDetail(item)">
                <td>{{item.id}}</td>
                <td>{{item.name}}</td>
                <td>{{item.product_type_name}}</td>
                <td>{{formatValue1(item.value)}}</td>
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
        <!-- End Products List -->

        <!-- Pagination -->
        <Pagination v-if="listProducts.length > 0" :list="listProducts" @updatePaginatedItems="updatePaginatedProducts"/>
      </div>

      <!-- Modal Product Detail -->
      <div class="modal fade" id="productDetail" tabindex="-1" aria-labelledby="productDetailLabel" aria-hidden="true" data-bs-backdrop="static">
        <!-- <div class="modal-dialog" style="width: 600px;"> -->
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="productDetailLabel"><b>ID: {{ modalProductData.id }}</b></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="clearProductModal()"></button>   
            </div>
            <div class="modal-body">
              <!-- Alerts -->
              <Alerts v-if="alert" :alert="alert" :alertMsg="alertMsg" :alertStatus="alertStatus"/>  

              <!-- Dados/Editar/Deletar -->
              <div>
                <div style="margin-top: -10px; margin-bottom: 5px;">
                  <button v-if="!editProduct" type="button" class="btn btn-outline-success btn-sm" @click="editProduct = !editProduct"><i class="bi bi-pencil"></i></button>
                  <button v-else type="button" class="btn btn-success btn-sm" @click="editProduct = !editProduct"><i class="bi bi-pencil"></i></button>
                </div>

                <table class="table table-striped table-hover">            
                  <tbody>
                    <tr><th scope="row">Nome</th><td><input type="text" v-model="editFormProductName" class="form-control" :disabled="!editProduct"></td></tr>
                    <tr><th scope="row">Tipo</th>
                      <td>
                        <select class="form-select" v-model="editFormProductType" :disabled="!editProduct">                      
                          <option v-for="item in listProductTypes" :value="item.id" :key="item.id">{{item.name}}</option>
                        </select>
                      </td>
                    </tr>                
                    <tr><th scope="row">Valor</th><td><input type="text" v-model="editMaskValue" class="form-control" :disabled="!editProduct"></td></tr>                    
                    <tr><th scope="row">Descrição</th><td><textarea type="text" class="form-control" v-model="editFormProductDescription" id="productDescription" cols="30" rows="2" placeholder="Nome do produto" :disabled="!editProduct"></textarea></td></tr>    

                  </tbody>
                </table>

                <div v-if="editProduct" class="text-end">
                  <button v-if="!btnEdit" type="button" class="btn btn-success m-1" @click="updateProduct()">Salvar alteração</button>
                  <button v-else type="button" class="btn btn-success m-1" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...</button>
                </div>

                <div v-if="!editProduct" style="margin-bottom: 10px;">
                  <button @click="confirmDelete = true" v-if="!confirmDelete" type="button" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash"></i> Deletar produto</button>
                  <div v-if="confirmDelete">
                    <span class="text-danger"><b>Deseja realmente deletar esse produto?</b></span><br>
                    <button v-if="!btnDelete" type="button" class="btn btn-danger btn-sm m-1" @click="deleteProduct(modalProductData.id)">Confirmar</button>
                    <button v-else type="button" class="btn btn-danger btn-sm m-1" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...</button>
                    <button @click="confirmDelete = false" v-if="!btnDelete" class="btn btn-secondary btn-sm">Cancelar</button>
                  </div>           
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!-- End Product Detail Modal -->

    </div><br>
    <!-- End Products -->

    <!-- Product Types -->
    <div class="card">
      <div class="card-body">

        <!-- New Product Type -->
        <div>
          <div v-if="!divNewProductType" style="margin-bottom: 10px;">
            <button class="btn btn-primary" @click="divNewProductType = !divNewProductType">Novo Tipo de produto</button>
          </div>
          <div v-if="divNewProductType">
            <div class="text-center">
              <h4>Novo Tipo de Produto</h4>
            </div>
            <div class="text-end">
              <button class="btn btn-danger" @click="divNewProductType = !divNewProductType">Cancelar</button>
            </div>          
          </div>
          
          <form @submit.prevent="createProductType()" class="row" v-if="divNewProductType">
            <div class="col-md-6">
              <label for="productName" class="form-label">Nome</label>
              <input type="text" class="form-control" v-model="formProductTypeName" id="productName" placeholder="Nome do produto" required>
            </div>

            <div class="col-md-6">
              <label for="productValue" class="form-label">Taxa</label>            
              <input type="text" :value="maskTax" @input="formatTax($event.target.value)"  class="form-control" id="productValue" placeholder="0.50%" required>            
            </div>

            <div class="text-end" style="margin-top: 10px; margin-bottom: 10px;">
              <button v-if="!loadingBtn" type="submit" class="btn btn-primary">Salvar</button>
              <button v-else class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...
              </button>
            </div>
            <hr>       

          </form>
        </div>
        <!-- End New Product Type -->

        <!-- Product Types List -->
        <h5 class="card-title text-center">Lista Tipos de Produto</h5>
        <div class="table-responsive">
          <table class="table table-hover text-center">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Taxa</th>
                <th scope="col">Ação</th>
              </tr>
            </thead>
            <tbody v-if="paginatedProductTypes.length > 0">
              <tr v-for="item in paginatedProductTypes" :key="item.id">
                <td>{{item.id}}</td>
                <td>{{item.name}}</td>
                <td>{{formatTax1(item.tax)}}</td>
                <td title="Desativar Tipo de produto.">
                  <button v-if="!btnDeleteType" type="button" class="btn btn-danger btn-sm m-1" @click="deleteProductType(item.id)"><i class="bi bi-trash"></i></button>
                  <button v-else type="button" class="btn btn-danger btn-sm m-1" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...</button>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td v-if="loadingRequest1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
                <td v-if="loadingRequest1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></td>
                <td v-else> - </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- End Product Types List -->

        <!-- Pagination -->
        <Pagination v-if="listProductTypes.length > 0" :list="listProductTypes" @updatePaginatedItems="updatePaginatedProductTypes"/>
      </div>
    </div>
    <!-- End Product Types-->

  </div>
</template>

<script>
import { apiJson, apiMult } from "@/services/api"
import Alerts from "@/components/Alerts.vue"
import Pagination from "@/components/Pagination.vue"

export default {
  name: 'ProductsView',
  data() {
    return {
      loadingRequest: false,
      loadingRequest1: false,
      loadingBtn: false,
      alert: false,
      alertMsg: '',
      alertStatus: false,
      listProducts: [],
      listProductTypes: [],      
      divNewProduct: false,
      formProductName: '',
      formProductType: '',
      formProductDescription: '',
      formProductValue: '',
      maskValue: '',
      divNewProductType: false,
      formProductTypeName: '',
      formProductTypeTax: '',
      maskTax: '',
      paginatedProducts: [],
      paginatedProductTypes: [],
      modalProductData: [],
      editFormProductName: '',
      editFormProductType: '',
      editFormProductDescription: '',
      editFormProductValue: '',
      editMaskValue: '',
      editProduct: false,
      btnEdit: false,
      confirmDelete: false,
      btnDelete: false,
      btnDeleteType: false,
    }
  },  
  components: {
    Alerts,
    Pagination
  },
  mounted () {
    this.getListProducts()
    this.getListProductsType()
  },
  watch: {
    maskValue: function () {
      let numeros = this.maskValue.replace(/\D/g, '').replace(',', '.') // Remove todos os caracteres que não são dígitos (números) e troca , por .
      this.formProductValue = parseFloat(numeros) / 100
    },
    editMaskValue: function () {
      this.editFormatValue(this.editMaskValue)

      let numeros = this.editMaskValue.replace(/\D/g, '').replace(',', '.') // Remove todos os caracteres que não são dígitos (números) e troca , por .
      this.editFormProductValue = parseFloat(numeros) / 100
    },
    maskTax: function () {
      let numeros = this.maskTax.replace(/\D/g, '').replace(',', '.') // Remove todos os caracteres que não são dígitos (números) e troca , por .
      this.formProductTypeTax = parseFloat(numeros) / 100
      // console.log(this.formProductTypeTax)
    },
    divNewProduct: function () {
      if (!this.divNewProduct) {
        this.formProductName = ''
        this.formProductType = ''
        this.formProductDescription = ''
        this.formProductValue = ''
        this.maskValue = ''
        this.loadingBtn = false
      }
    },
    divNewProductType: function () {
      if (!this.divNewProduct) {
        this.formProductTypeName = ''
        this.formProductTypeTax = ''
        this.maskTax = ''
        this.loadingBtn = false
      }
    }
  },
  methods: {
    createProduct () {
      this.loadingBtn = true

      if (this.formProductName == '' || this.formProductType == '' || this.formProductDescription == '' || this.formProductValue == '') {
        this.alertMsg = 'Preencha corretamente todos os campos'
        this.alert = true
        this.alertStatus = false
        this.loadingBtn = false
      }

      let formData = new FormData()
      formData.append('name', this.formProductName)
      formData.append('product_type_id', this.formProductType)
      formData.append('description', this.formProductDescription)
      formData.append('value', this.formProductValue)

      apiJson.post('product-create', formData).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }
        
        setTimeout(() => {
          this.getListProducts()
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
    createProductType () {
      this.loadingBtn = true
      
      if (this.formProductTypeName == '' || this.formProductTypeTax == '') {
        this.alertMsg = 'Preencha corretamente todos os campos'
        this.alert = true
        this.alertStatus = false
        this.loadingBtn = false
      }

      let formData = new FormData()
      formData.append('name', this.formProductTypeName)
      formData.append('tax', this.formProductTypeTax)

      apiJson.post('product-type-create', formData).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }

        setTimeout(() => {
          this.getListProducts()
          this.getListProductsType()
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
    getListProducts () {
      this.listProducts = []
      this.divNewProduct = false
      this.loadingRequest = true

      apiJson.get('products-list').then((response) => {
        this.listProducts = response.data.data
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
    getListProductsType () {
      this.listProductTypes = []
      this.divNewProductType = false
      this.loadingRequest1 = true
      apiJson.get('product-types-list').then((response) => {
        this.listProductTypes = response.data.data
        this.loadingRequest1 = false
      }).catch((error) => {
        if (error.response) {
          if(error.response.status == 404) {
            this.alertMsg = error.response.data.message
            this.alert = true
            this.alertStatus = false
            this.loadingBtn = false
          }
          
        } else {
          console.log(error)
        }
        this.loadingRequest1 = false
      })
    },
    productDetail (data) {
      this.modalProductData = data
      this.editFormProductName = data.name
      this.editFormProductType = data.product_type_id
      this.editFormProductDescription = data.description
      this.editMaskValue = this.formatValue1(data.value)
    },
    updateProduct () {
      this.btnEdit = true

      let formData = new FormData()
      
      // Valida o que foi alterado
      this.listProducts.forEach(item => {
        if(item.id == this.modalProductData.id) {
          formData.append('id', this.modalProductData.id)

          if (item.name != this.editFormProductName) {
            formData.append('name', this.editFormProductName)
          }
          if (item.name != this.editFormProductName) {
            formData.append('product_type_id', this.editFormProductType)
          }
          if (item.name != this.editFormProductName) {
            formData.append('description', this.editFormProductDescription)
          }
          if (item.name != this.editFormProductName) {
            formData.append('value', this.editFormProductValue)
          }
        }
      });

      apiJson.post('product-update', formData).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }
        
        setTimeout(() => {
          this.editProduct = false
          this.getListProducts()
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
          this.alertMsg = "Erro ao atualizar produto."
          this.alert = true
          this.alertStatus = false
          this.loadingBtn = false
          console.log(error)
        }
        this.btnEdit = false
      })
    },
    deleteProduct (id) {
      this.btnDelete = true

      apiJson.get('product-delete/'+id).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }
        
        setTimeout(() => {
          window.location.reload()
        }, 2000)
      }).catch((error) => {
        console.log(error)
      })
    },
    deleteProductType (id) {
      this.btnDeleteType = true

      apiJson.get('product-type-delete/'+id).then((response) => {
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
        }
        
        setTimeout(() => {
          window.location.reload()
        }, 2000)
      }).catch((error) => {
        console.log(error)
      })
    },
    clearProductModal () {
      this.modalProductData = []
      this.editMaskValue = ''
      this.editProduct = false
      this.confirmDelete = false
      this.alert = false
    },
    formatValue (value) {
      // Remove todos os caracteres que não são números
      const numeros = value.replace(/\D/g, '')

      // Converte o valor para número
      const numero = parseFloat(numeros) / 100 // Dividido por 100 para transformar em centavos

      // Formata o valor como dinheiro
      this.maskValue = numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
      })
        
    },
    editFormatValue (value) {
      // Remove todos os caracteres que não são números
      const numeros = value.replace(/\D/g, '')

      // Converte o valor para número
      const numero = parseFloat(numeros) / 100 // Dividido por 100 para transformar em centavos

      this.editMaskValue = numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
      })
      
    },
    formatValue1 (value) {
      // Remove todos os caracteres que não são números
      const numeros = value.replace(/\D/g, '')

      // Converte o valor para número
      const numero = parseFloat(numeros) / 1000 // Dividido por 100 para transformar em centavos      

      // Formata o valor como dinheiro
      return value = numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
      })
    },
    formatTax (value) {
      // Remove todos os caracteres que não são números
      const numeros = value.replace(/\D/g, '')

      // Converte o valor para número e divide por 100 para transformar em percentual
      const percentual = parseFloat(numeros) / 100

      // Formata o valor como percentual com duas casas decimais
      this.maskTax = `${percentual.toFixed(2)}%`      
    },
    formatTax1 (value) {
      // Remove todos os caracteres que não são números
      const numeros = value.replace(/\D/g, '')

      // Converte o valor para número e divide por 100 para transformar em percentual
      const percentual = parseFloat(numeros) / 1000

      // Formata o valor como percentual com duas casas decimais
      return value = `${percentual.toFixed(2)}%`      
    },
    updatePaginatedProducts (newPaginatedItems) {
      this.paginatedProducts = newPaginatedItems;
    },
    updatePaginatedProductTypes (newPaginatedItems) {
      this.paginatedProductTypes = newPaginatedItems;
    }
  }
}
</script>