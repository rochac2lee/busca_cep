import axios from 'axios'

/*
** Criando a instância do axios
*/
const api = axios.create({
  baseURL: "http://localhost:8005/api"
})

export default api
