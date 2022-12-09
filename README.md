# Laravel Postgis Boilerplate

Webmapp's Starting point

## Laravel 9 Project based on Nova 4

### Inizializzazione tramite boilerplate

- Download del codice del boilerplate in una nuova cartella `nuovoprogetto` e disattivare il collegamento tra locale/remote:
  ```sh
  git clone https://github.com/webmappsrl/laravel-postgis-boilerplate.git nuovoprogetto
  cd nuovoprogetto
  git remote remove origin
  ```
- Effettuare il link tra la repository locale e quella remota (repository vuota github)

  ```sh
  git remote add origin git@github.com:username/repo.git
  ```

- Copy file `.env-example` to `.env`

  Questi valori nel file .env sono necessari per avviare l'ambiente docker. Hanno un valore di default e delle convenzioni associate, valutare la modifica:

  - `APP_NAME` (it's php container name and - postgrest container name, no space)
  - `DOCKER_PHP_PORT` (Incrementing starting from 9100 to 9199 range)
  - `DOCKER_SERVE_PORT` (always 8000)
  - `DOCKER_PROJECT_DIR_NAME` (it's the folder name of the project)
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`

- Creare l'ambiente docker
  ```sh
  cd docker
  bash init-docker.sh
  ```
- Digitare `y` durante l'esecuzione dello script per l'installazione di xdebug

- Verificare che i container si siano avviati
  ```sh
  docker ps
  ```

- Avvio di una bash all'interno del container php per installare tutte le dipendenze e lanciare il comando php artisan serve
  ```sh
  docker exec -it php81_$nomeApp bash
  composer install
  php artisan serve --host 0.0.0.0
  ```

- A questo punto l'applicativo è in ascolto su <http://127.0.0.1:8000>



### Scripts

Ci sono vari scripts per il deploy nella cartella `scripts`. Per lanciarli basta lanciare una bash con la path dello script dentro il container php, eg:

```bash
docker exec -it php81_$nomeApp bash scripts/deploy_dev.sh
```

### Problemi noti

Durante l'esecuzione degli script potrebbero verificarsi problemi di scrittura su certe cartelle, questo perchè di default l'utente dentro il container è `www-data (id:33)` quando invece nel sistema host l'utente ha id `1000`. Ci sono 2 possibili soluzioni:

- Chown/chmod della cartella dove si intende scrivere, eg:
  ```bash
    chown -R 33 storage
  ```

- Utilizzare il parametro `-u` per il comando `docker exec` così da specificare l'id utente, eg come utente root:
  ```bash
  docker exec -u 0 -it php81_$nomeApp bash scripts/deploy_dev.sh
  ```

