<template>
  <v-container>
    <h1 class="text-h6 font-weight-bold mb-5">Pesquisa de Endereço</h1>
    <v-form @submit.prevent="search">
      <v-row class="d-flex align-center">
        <v-col cols="12" md="4" lg="2">
          <v-text-field
            v-model="params.cep"
            label="CEP"
            color="primary"
            outlined
            rounded
            v-mask="'#####-###'"
            hide-details
          />
        </v-col>
        <v-col cols="12" md="4" lg="3">
          <v-text-field
            v-model="params.street"
            label="Rua (Logradouro)"
            color="primary"
            outlined
            rounded
            hide-details
          />
        </v-col>
        <v-col cols="12" md="4" lg="2">
          <v-btn class="primary" rounded :loading="loading" type="submit">
            Buscar
          </v-btn>
        </v-col>
      </v-row>
    </v-form>

    <!-- Resultados da pesquisa -->
    <v-divider class="my-7"></v-divider>

    <v-row v-if="result" class="px-3">
      <v-col cols="12">
        <v-data-table :items="result" :headers="tableHeaders">
          <template v-slot:item="{ item }">
            <tr>
              <td v-for="(value, key) in item" :key="key">
                <template v-if="attributeMap[key]">
                  <div>
                    {{ value }}
                  </div>
                </template>
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-col>
      <v-col cols="12" md="2" lg="2" class="px-0">
        <v-btn class="grey lighten-2 mx-0" elevation="0" rounded @click="clear">
          Limpar
        </v-btn>
      </v-col>
    </v-row>

    <v-row v-else>
      <v-col cols="12">
        <p class="text-body-1">Nenhum resultado encontrado.</p>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { eventbus } from "@/main.js";

export default {
  name: "Search",
  data() {
    return {
      loading: false,
      params: {},
      result: null,
      attributeMap: {
        id: "Cod",
        zip_code: "CEP",
        street: "Logradouro",
        number: "Número",
        complement: "Complemento",
        district: "Bairro",
        city: "Cidade",
        uf: "UF"
      }
    };
  },
  computed: {
    tableHeaders() {
      const headers = [];
      for (const key in this.attributeMap) {
        headers.push({
          text: this.attributeMap[key],
          value: key
        });
      }
      return headers;
    }
  },
  methods: {
    search() {
      this.loading = true;
      let endpoint = "addresses";
      if (this.params.cep) {
        endpoint = `${endpoint}/${this.params.cep}`;
      }
      if (this.params.street) {
        endpoint = `${endpoint}/search?term=${this.params.street}`;
      }

      this.$api
        .get(endpoint, this.params)
        .then(res => {
          this.result = res.data.data;
        })
        .catch(err => {
          eventbus.toggleSnackbar({
            show: true,
            text: err.response.data.message,
            color: "danger"
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    clear() {
      this.params = {};
      this.result = null;
    }
  }
};
</script>
