import axios from 'axios'

/*
** Criando a instância do axios
*/
const api = axios.create({
  baseURL: process.env.VUE_APP_BASE_API_URL
})

export default api
