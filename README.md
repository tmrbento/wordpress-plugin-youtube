
# **Projeto: Plugin WordPress - Integração com API do YouTube**

Este projeto cria um ambiente WordPress utilizando Docker e Docker Compose, com um plugin customizado que integra com a API do YouTube. O plugin lista vídeos de um canal específico e cria páginas no WordPress para vídeos cujo título contém a palavra "aula".

---

## **1. Estrutura do Projeto**

```
projeto/
├── docker-compose.yml        # Arquivo de configuração do Docker Compose
├── README.md                 # Documentação do projeto
├── plugin-youtube/           # Diretório do plugin customizado
│   ├── youtube-plugin.php    # Arquivo principal do plugin
│   ├── includes/             # Lógica do plugin em classes PHP
│   │   ├── YouTubeAPI.php
│   │   └── VideoPageCreator.php
```

---

## **2. Pré-requisitos**

Para rodar este projeto, você precisa:

- **Docker** instalado - [Instale o Docker](https://www.docker.com/get-started)
- **Docker Compose** instalado - [Instale o Docker Compose](https://docs.docker.com/compose/)
- Chave de API do YouTube - [Obter API Key](https://console.cloud.google.com/)

---

## **3. Configuração do Ambiente**

### **Clonando o Projeto**

```bash
git clone https://github.com/seu-usuario/projeto-wordpress-youtube.git
cd projeto-wordpress-youtube
```

### **Configurando o Docker Compose**

1. No arquivo `docker-compose.yml`, certifique-se de que a configuração dos volumes está correta:

   ```yaml
   volumes:
     - ./wordpress-data:/var/www/html/
     - ./youtube-integration-plugin:/var/www/html/wp-content/plugins/youtube-integration-plugin
   ```

2. O WordPress será instalado e a pasta `youtube-integration-plugin` será montada diretamente na pasta de plugins do WordPress.

---

## **4. Subindo o Ambiente**

Execute o seguinte comando para iniciar os contêineres:

```bash
docker-compose up -d
```

### **Acesso aos Serviços**

- **WordPress**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8081](http://localhost:8081)

---

## **5. Instalando e Configurando o Plugin**

1. Acesse o painel administrativo do WordPress:
   - URL: `http://localhost:8080/`
   - Instale o Wordpress
   - Acesse o painel

2. Ative o plugin **YouTube Integration Plugin**.
   - Acesse o menu Plugins;
   - Ative o plugin "youtube-integration-plugin"

3. Vá até o menu **YouTube Integration** no painel administrativo.

4. Insira:
   - **Chave da API do YouTube**.
   - **ID do Canal do YouTube**.

5. Salve as configurações.

---

## **6. Funcionamento do Plugin**

- O plugin utiliza a **API do YouTube v3** para listar vídeos do canal especificado.
- Ele filtra os vídeos cujo título contém a palavra **"aula"**.
- Para cada vídeo encontrado, o plugin cria uma **página no WordPress** com o título do vídeo e o vídeo incorporado.

---

## **7. Desenvolvimento do Plugin**

O plugin segue o padrão **orientado a objetos** com as seguintes classes:

- **YouTubeAPI**: Responsável por se comunicar com a API do YouTube.
- **VideoPageCreator**: Cria páginas no WordPress para os vídeos filtrados.

---

## **8. Comandos Úteis**

- **Subir os contêineres**:
   ```bash
   docker-compose up -d
   ```

- **Parar os contêineres**:
   ```bash
   docker-compose down
   ```

- **Verificar logs dos contêineres**:
   ```bash
   docker-compose logs
   ```

---

## **9. Tecnologias Utilizadas**

- **WordPress**: CMS para gerenciamento de conteúdo.
- **Docker** e **Docker Compose**: Ambiente isolado de desenvolvimento.
- **PHP**: Linguagem de programação para o plugin.
- **YouTube Data API v3**: Integração com o YouTube.

---

## **10. Licença**

Este projeto é disponibilizado sob a **MIT License**.

---
