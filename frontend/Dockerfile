# Definindo a imagem base
FROM node:14

# Definindo o diretório de trabalho dentro do container
WORKDIR /app/frontend

# Copiando os arquivos necessários para o container
COPY package.json yarn.lock /app/frontend/

# Instalando as dependências com o Yarn
RUN yarn install

# Expondo a porta que o aplicativo Vue.js utiliza
EXPOSE 8080

# Comando padrão para iniciar o aplicativo Vue.js com yarn serve
CMD ["yarn", "serve"]
