# Laravel Postgis Boilerplate

Punto di partenza di Webmapp

## Laravel 11 basato su Nova 5

Boilerplate per Laravel 11 basato su PHP 8.4 e Postgres + PostGIS. Supporto locale per web server PHP e Xdebug oltre a:

-   redis
-   elasticsearch

per la versione di produzione e:

-   mailpit
-   minio

per la versione di sviluppo

## INSTALLAZIONE

Prima di tutto, installa il repository [GEOBOX](https://github.com/webmappsrl/geobox) e configura il [comando ALIASES](https://github.com/webmappsrl/geobox#aliases-and-global-shell-variable).  
Sostituisci `${instance name}` con il nome dell'istanza (APP_NAME nel file .env-example).

```sh
git clone git@github.com:webmappsrl/${repository_name}.git ${instance name}
```

### Nota importante: ricordati di eseguire il checkout del branch develop.

```sh
cd ${instance name}
bash docker/init-docker.sh
docker exec -u 0 -it php_${instance name} bash
chown -R 33 storage
```

### Se hai installato XDEBUG, crea il file xdebug.log nel container Docker

```sh
docker exec -u 0 -it php_${instance name} bash
touch /var/log/xdebug.log
chown -R 33 /var/log/
```

### Installa le dipendenze

Avvia una bash all'interno del container php per installare tutte le dipendenze (utilizzare `APP_NAME` al posto di `$nomeApp`):

```sh
docker exec -it php_$nomeApp bash
composer install
php artisan key:generate
php artisan optimize
php artisan migrate
php artisan jwt:secret
```

#### Nota:

-   Per completare l'installazione di Laravel Nova, é necessario fornire le credenziali di accesso.
  
### Differenze ambiente produzione locale

Questo sistema di container docker è utilizzabile sia per lo sviluppo locale sia per un sistema in produzione.

Di fatto il comando init-docker per utilizzare l'ambiente prod usa:

```sh
docker compose up -d
```

Per l'ambiente dev/locale invece:

```sh
docker compose -f develop.compose.yml up -d
```

In locale abbiamo queste caratteristiche:

-   la possibilità di lanciare il processo processo `composer run dev` all'interno del container phpfpm, quindi la configurazione della porta `DOCKER_SERVE_PORT` (default: `8000`) e `DOCKER_VITE_PORT` (default:`5173`) necessaria al progetto. Se servono più istanze laravel con processo artisan serve contemporaneamente in locale, valutare di dedicare una porta tcp dedicata ad ognuno di essi. Per fare questo basta solo aggiornare `DOCKER_SERVE_PORT` e `DOCKER_VITE_PORT`.
-   la presenza di xdebug, definito in fase di build dell'immagine durante l'esecuzione del comando
-   `APP_ENV=local`, `APP_DEBUG=true` e `LOG_LEVEL=debug` che istruiscono laravel su una serie di comportamenti per il debug e l'esecuzione locale dell'applicativo
-   Una password del db con complessità minore. **In produzione usare [password complesse](https://www.avast.com/random-password-generator#pc)**

### Inizializzazione tramite boilerplate

-   Download del codice del boilerplate in una nuova cartella `nuovoprogetto` e disattivare il collegamento tra locale/remote:
    ```sh
    git clone https://github.com/webmappsrl/laravel-postgis-boilerplate.git nuovoprogetto
    cd nuovoprogetto
    git remote remove origin
    ```
-   Effettuare il link tra la repository locale e quella remota (repository vuota github)

    ```sh
    git remote add origin git@github.com:username/repo.git
    ```

-   Copy file `.env-example` to `.env`

    Questi valori nel file .env sono necessari per avviare l'ambiente docker. Hanno un valore di default e delle convenzioni associate, valutare la modifica:

    -   `APP_NAME` (it's php container name and - postgrest container name, no space)
    -   `DOCKER_PHP_PORT` (Da 9100 a 9199. Per il sistema operativo MAC si può valutare le porte già occupate con `lsof -iTCP -sTCP:LISTEN`, su linux invece `nmap -atunp | grep LISTEN`)
    -   `DOCKER_SERVE_PORT` (default 8000, solo in locale)
    -   `DOCKER_VITE_PORT` ( default 5173, solo in locale, la porta dove è possibile recuperare gli asset compilati da vite)
    -   `DOCKER_PROJECT_DIR_NAME` (è il nome della cartella del progetto)
    -   `DB_DATABASE`
    -   `DB_USERNAME`
    -   `DB_PASSWORD`

-   Una volta compilato correttamente il file .env, tirare sù l'ambiente docker:
    ```sh
    bash docker/init-docker.sh
    ```
-   Rispondere alle domande poste dallo script con n o y

-   Verificare che i container si siano avviati

    ```sh
    docker ps
    ```

-   Avvio di una bash all'interno del container php per installare tutte le dipendenze e lanciare il comando php artisan serve (utilizzare `APP_NAME` al posto di `$nomeApp`):

    ```sh
    docker exec -it php_$nomeApp bash
    composer install
    php artisan key:generate
    php artisan optimize
    php artisan migrate
    php artisan serve --host 0.0.0.0
    ```

-   A questo punto l'applicativo è in ascolto su <http://127.0.0.1:8000> (la porta è quella definita in `DOCKER_SERVE_PORT`)

### Configurazione xdebug vscode (solo in locale)

Assicurarsi di aver installato l'estensione [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug).

Una volta avviato il container con xdebug configurare il file `.vscode/launch.json`, in particolare il `pathMappings` tenendo presente che **sulla sinistra abbiamo la path dove risiede il progetto all'interno del container**, `${workspaceRoot}` invece rappresenta la pah sul sistema host. Eg:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9200,
            "pathMappings": {
                "/var/www/html/geomixer2": "${workspaceRoot}"
            }
        }
    ]
}
```

Aggiornare `/var/www/html/geomixer2` con la path della cartella del progetto nel container phpfpm.

Per utilizzare xdebug **su browser** utilizzare uno di questi 2 metodi:

-   Installare estensione xdebug per browser [Xdebug helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
-   Utilizzare il query param `XDEBUG_SESSION_START=1` nella url che si vuole debuggare
-   Altro, [vedi documentazione xdebug](https://xdebug.org/docs/step_debug#web-application)

Invece **su cli** digitare questo prima di invocare il comando php da debuggare:

```bash
export XDEBUG_SESSION=1
```

### Problemi noti

Durante l'esecuzione degli script potrebbero verificarsi problemi di scrittura su certe cartelle, questo perchè di default l'utente dentro il container è `www-data (id:33)` quando invece nel sistema host l'utente ha id `1000`:

-   Chown/chmod della cartella dove si intende scrivere, eg:

    NOTA: per eseguire il comando chown potrebbe essere necessario avere i privilegi di root. In questo caso si deve effettuare l'accesso al cointainer del docker utilizzando lo specifico utente root (-u 0). Questo è valido anche sbloccare la possibilità di scrivere nella cartella /var/log per il funzionamento di Xdedug

    Utilizzare il parametro `-u` per il comando `docker exec` così da specificare l'id utente, eg come utente root (utilizzare `APP_NAME` al posto di `$nomeApp`):

    ```bash
    docker exec -u 0 -it php_$nomeApp bash
    chown -R 33 storage
    ```

Xdebug potrebbe non trovare il file di log configurato nel .ini, quindi generare vari warnings

-   creare un file in `/var/log/xdebug.log` all'interno del container phpfpm. Eseguire un `chown www-data /var/log/xdebug.log`. Creare questo file solo se si ha esigenze di debug errori xdebug (impossibile analizzare il codice tramite breakpoint) visto che potrebbe crescere esponenzialmente nel tempo
