<template>
  <div class="d-flex justify-content-center mt-3">
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item" v-if="currentPage > 1">
          <a class="page-link" href="#" aria-label="Previous" @click="prevPage">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
          <a class="page-link" href="#" @click="gotoPage(page)">{{ page }}</a>
        </li>
        <li class="page-item" v-if="currentPage < totalPages">
          <a class="page-link" href="#" aria-label="Next" @click="nextPage">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>       
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: ['list'],
  data() {
    return {
      itemsPerPage: 4, // Defina o número de itens por página aqui
      currentPage: 1,
    }
  },
  mounted () {
    this.$emit('updatePaginatedItems', this.paginatedItems);
  },
  computed: {
    paginatedItems() {
      // Adicione uma etapa de ordenação por ID aqui
      const sortedList = this.list.slice().sort((a, b) => a.id - b.id);

      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return sortedList.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.list.length / this.itemsPerPage);
    }
  },
  methods: {
    emitPaginatedItems() {
      this.$emit('updatePaginatedItems', this.paginatedItems);
    },
    gotoPage (page) {
      this.currentPage = page;
      this.emitPaginatedItems()
    },
    prevPage () {
      this.currentPage--;
      this.emitPaginatedItems()
    },
    nextPage () {
      this.currentPage++;
      this.emitPaginatedItems()
    }
  }
}
</script>