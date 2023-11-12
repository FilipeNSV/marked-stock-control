<template>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4" style="margin-top: -100px;">
      <span class="badge bg-dark text-white" style="border: 2px solid #bdc3c7; margin-bottom: 50px;"><h2><i class="bi bi-shop"></i> Controle de Mercado</h2></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="card mb-3 border-0">

              <div class="card-body text-white" style="background-color: #1b1c1a;border: 2px solid #bdc3c7;">

                <!-- Alerts -->
                <Alerts v-if="alert" :alert="alert" :alertMsg="alertMsg" :alertStatus="alertStatus"/>
                
                <!-- Formulário de Login -->
                <form v-if="!registerForm" class="row g-3 needs-validation" @submit.prevent="login()">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Faça seu login</h5>
                    <p class="text-center small">Entre com seu E-mail e Senha</p>
                  </div>

                  <div class="col-12">
                    <label class="form-label">E-mail</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                      <input type="email" v-model="formEmail" class="form-control" placeholder="Ex.: email@exemplo.com" required/>

                    </div>
                  </div>

                  <div class="col-12">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-key"></i></span>
                      <input type="password" v-model="formPassword" class="form-control" required/>
                    </div>                    
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit" v-if="loadingRequest == false">Login</button>
                    <button class="btn btn-primary w-100" type="button" disabled v-else>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Aguarde...
                    </button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Não tem cadastro? <a href="#" @click="registerForm = !registerForm">Cadastre-se!</a></p>
                  </div>
                </form>

                <!-- Formulário de Registro -->
                <form v-else class="row g-3 needs-validation" @submit.prevent="register()">
                  
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Criar conta</h5>
                    <p class="text-center small">Preencha os campos com seus dados</p>
                  </div>

                  <div class="col-12">
                    <label for="name" class="form-label">Nome*</label>
                    <input type="text" v-model="formName" class="form-control" id="name" required/>
                  </div>

                  <div class="col-12">
                    <label for="email" class="form-label">E-mail*</label>
                    <input type="email" v-model="formEmail" class="form-control" id="email" required/>
                  </div>

                  <div class="col-12">
                    <label for="password" class="form-label">Senha*</label>
                    <input type="password" v-model="formPassword" class="form-control" id="password" required/>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit" v-if="loadingRequest == false">Registrar</button>
                    <button class="btn btn-primary w-100" type="button" disabled v-else>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Aguarde...
                    </button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Já possui um cadastro? <a href="#" @click="registerForm = !registerForm">Log in</a></p>
                  </div>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>

    </section>

  </div>
</template>

<script>
import { apiJson, apiMult } from "@/services/api"
import Alerts from "@/components/Alerts.vue"

export default {
  name: 'RegisterLoginView',
  data() {
    return {
      loadingRequest: false,
      alert: false,
      alertMsg: '',
      alertStatus: false,
      formName: '',
      formEmail: '',
      formPassword: '',
      registerForm: false
    }
  },
  components: {
    Alerts
  },
  watch: {
    registerForm: function (oldValue,newValue) {
      if (oldValue != newValue) {
        this.clearLogs()
      }
    }
  },
  methods: {
    login () {
      let vm = this
      this.loadingRequest = true

      if (this.formEmail == '') {
        this.alert = true 
        this.alertMsg = `Preencha o campo "Nome" corretamente!`
        this.loadingRequest = false
        return
      }
  
      if (this.formPassword == '') {
        this.alert = true 
        this.alertMsg = `Preencha o campo "Senha" corretamente!`
        this.loadingRequest = false
        return
      }

      let formData = new FormData()
      formData.append('email', this.formEmail)
      formData.append('password', this.formPassword)

      apiJson.post('user-login', formData).then((response) => {    
        if (response.data.error) {
          this.alertMsg = response.data.error
          this.alert = true
          this.alertStatus = response.data.status
          this.loadingRequest = false
          return
        }

        if (response.data.token) {
          let token = response.data.token
          let name = response.data.name
          localStorage.setItem("token", token)
          localStorage.setItem("name", name)
     
          setTimeout(function() {
            window.location.href = '/inicio'
          }, 2000)
        }
      }).catch((error) => {
        this.alert = true
        this.alertMsg = error.response.data.message
        this.alertStatus = false
        this.loadingRequest = false
      })    
    },
    register () {
      let vm = this
      this.loadingRequest = true

      if (this.formEmail == '') {
        this.alert = true 
        this.alertMsg = `Preencha o campo "Nome" corretamente!`
        this.loadingRequest = false
        return
      }
  
      if (this.formPassword == '') {
        this.alert = true 
        this.alertMsg = `Preencha o campo "Senha" corretamente!`
        this.loadingRequest = false
        return
      }

      let formData = new FormData()
      formData.append('name', this.formName)
      formData.append('email', this.formEmail)
      formData.append('password', this.formPassword)

      apiJson.post('user-create', formData).then((response) => {    
        if (response.data.status == 'success') {
          this.alertMsg = response.data.message
          this.alert = true
          this.alertStatus = true
          setTimeout(function() {
            vm.clearLogs()
            window.location.href = '/'
          }, 2000)          
        }

      }).catch((error) => {
        this.alert = true
        this.alertMsg = error.response.data.message
        this.alertStatus = false
        this.loadingRequest = false
      })
    },
    clearLogs () {
      this.formName = ''
      this.formEmail = ''
      this.formPassword = ''
      this.loadingRequest = false
      this.alert = false
      this.alertMsg = '',
      this.alertStatus = false
    }
  }
}
</script>

<style scoped>

</style>