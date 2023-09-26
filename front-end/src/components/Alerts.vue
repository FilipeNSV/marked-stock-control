<template>
  <!-- Alerts -->
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="alert == true && alertStatus == false">
      <strong>Atenção!</strong><br>{{alertMsg}}
      <button type="button" class="btn-close" @click="alert = false"></button>
    </div>
    <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="alert == true && alertStatus == true">
      <strong>{{alertMsg}}</strong>
      <button type="button" class="btn-close" @click="alert = false"></button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Alerts',
  props: ['alert', 'alertMsg', 'alertStatus'],
  
}
</script>