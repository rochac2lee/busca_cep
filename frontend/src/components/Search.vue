<template>
  <div>
    <v-container>
      <v-form @submit.prevent="search">
        <v-row>
          <v-col cols="12" md="2">
            <v-text-field
              v-model="params.cep"
              label="CEP"
              color="primary"
              outlined
              rounded
              v-mask="'#####-###'"
            />
          </v-col>
        </v-row>
      </v-form>
    </v-container>
  </div>
</template>

<script>
export default {
  name: "Search",
  data() {
    return {
      params: {}
    };
  }
};
</script>
