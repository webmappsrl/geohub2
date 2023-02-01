# Installazione ambiente di sviluppo

<!-- @import "[TOC]" {cmd="toc" depthFrom=1 depthTo=6 orderedList=false} -->

<!-- code_chunk_output -->

- [Installazione ambiente di sviluppo](#-installazione-ambiente-di-sviluppo)
  - [Metodo 1: 2 container per ogni istanza](#-metodo-1-2-container-per-ogni-istanza)
    - [Obiettivo](#-obiettivo)
      - [Il problema delle porte dei container](#-il-problema-delle-porte-dei-container)
      - [Il mounting della cartella wm-package](#-il-mounting-della-cartella-wm-package)
    - [Istruzioni](#-istruzioni)
    - [Risoluzione dei problemi](#-risoluzione-dei-problemi)
    - [Comandi utili](#-comandi-utili)
  - [Considerazioni su un ipotetico metodo 2: solo 2 container](#-considerazioni-su-un-ipotetico-metodo-2-solo-2-container)

<!-- /code_chunk_output -->

## Metodo 1: 2 container per ogni istanza

### Obiettivo

Avere up and running contemporaneamente l'ambiente di **sviluppo** per [`geohub2`](https://github.com/webmappsrl/geohub2), [`hoqu2`](https://github.com/webmappsrl/hoqu2) e [`wm-package`](https://github.com/webmappsrl/wm-package) tramite i container docker.

#### Il problema delle porte dei container

Poichè la configurazione iniziale dei container per gli ambienti `hoqu2` e `geohub2` prevede l'utilizzo delle porte di default `9100` per `phpfpm` e `8000` per il processo `php artisan serve` andremo a customizzarle per l'ambiente di geohub2 così da non generare conflitti. Ciò che andremo a modificare sono solo le porte esterne dei container, quelle interne rimarranno comunque le stesse (ovvero 9000 per `phpfpm` e 8000 per `php artisan serve`).

#### Il mounting della cartella wm-package

Una volta modificata la configurazione delle porte andremo a montare in entrambi i container la cartella `wm-package` così da consentirci lo sviluppo anche del package e contestualmente averlo aggiornato su entrambe le istanze.

### Istruzioni

git clone di tutte e tre le repo nella stessa cartella:

- hoqu
- geohub
- wm-package

modificare il file `composer.json` sia di `geohub2` che di `hoqu2` aggiungendo la repository locale inerente alla cartella wm-package:

```json
"repositories": [
        {
            "type": "path",
            "url": "../wm-package"
        }
    ]
```
copiare il file `.env-example` nel file `.env` su hoqu2

modificare le variabili di ambiente di geohub come segue (copiare il file .env-example .env):

```shell
DOCKER_PHP_PORT=9101
DOCKER_SERVE_PORT=8001
HOQU_URL=http://host.docker.internal:8000
```

modificare il file docker-compose.yml di geohub e hoqu come segue:

```yaml
volumes:
	- ".:/var/www/html/${DOCKER_PROJECT_DIR_NAME}"
	- "../wm-package:/var/www/html/wm-package"
```

aprire due finestre nel terminale. Nella prima posizionarsi nella cartella di hoqu2, nella seconda invece in quella di geohub2.
Lanciare in entrambe le finestre:

```shell
bash docker/init-docker.sh
```

Digitare 'y' per l'installazione di xdebug

Qualora ci fossero problemi nell'allocazione delle porte (eg: `Bind for 0.0.0.0:9101 failed: port is already allocated`), valutare di cambiare i parametri `DOCKER_PHP_PORT` e `DOCKER_SERVER_PORT` nel file `.env`.

Controllare che i container php/postgres siano up and running con il comando:

```shell
docker ps
```

ci dovrebbero essere tutti questi:

- `php81_geohub2`
- `postgres_geohub2`
- `php81_hoqu2`
- `postgres_hoqu2`

A questo punto lanciare nella prima finestra ancora aperta del terminale:

```shell
docker exec -it php81_hoqu2 bash
composer install
php artisan key:generate
php artisan optimize
php artisan migrate
php artisan serve --host 0.0.0.0
```

Alla fine assicurarsi che dall'ultimo comando risulti un messaggio di server in ascolto sulla porta 8000 (`Server running on [http://0.0.0.0:8000].`). Provare la visualizzazione dell'applicativo nel browser tramite url http://127.0.0.1:8000

Nella seconda finestra invece:

```shell
docker exec -it php81_geohub2 bash
composer install
php artisan key:generate
php artisan optimize
php artisan migrate
php artisan serve --host 0.0.0.0
```

Alla fine assicurarsi che dall'ultimo comando risulti un messaggio di server in ascolto sulla porta 8000 (`Server running on [http://0.0.0.0:8000].`). Provare la visualizzazione dell'applicativo nel browser tramite url http://127.0.0.1:8001

Adesso è possibile eseguire la procedura di registrazione utente hoqu2-geohub2 illustrata [qui](https://github.com/webmappsrl/hoqu2/tree/develop#procedura-di-inserimento-callerprocessor-su-hoqu). Dopo la procedura di registrazione, per verificare che tutto sia avvenuto correttamente, aprire una bash sul container phpfpm di hoqu2 ed aprire tinker:

```shell
php artisan tinker
```

digitando:

```php
User::all()
```

Dovranno risultare almeno due utenti:

- un register
- un geouser con l'ip del container di geohub

Lo sviluppo del package `wm-package` e quindi ad esempio il lancio del comando `composer test` può essere effettuato da qualsiasi dei due container phpfpm.

### Risoluzione dei problemi

Errore inerente ai permessi della cartella storage. Si risolve lanciando una bash come utente privilegiato all'interno del container php dell'istanza che ha problemi e quindi si cambiano i permessi dei file.

(sostituire asterisco con il nome dell'istanza container interessata)
```shell
docker exec -it -u 0 php81_* bash
chown -R www-data:www-data storage
```

Errore inerente alle path del progetto. Valutare un clear config (anche subito dopo aver cambiato il file .env)

```shell
php artisan config:clear
```

Impossibile connettere postgres client (pgAdmin, Dbeaver, psql ...). Verificare nome utente/password nel file .env dell'istanza. Verificare l'host con il seguente comando (sostituire `postgres_hoqu2` con il container postgres al quale ci si vuole collegare)

```shell
docker inspect postgres_hoqu2 --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
```

Xdebug potrebbe non trovare il file di log configurato nel .ini, quindi generare vari warnings

creare un file in `/var/log/xdebug.log` all'interno del container phpfpm. Eseguire un `chown www-data /var/log/xdebug.log`. Creare questo file solo se si ha esigenze di debug errori xdebug (impossibile analizzare il codice tramite breakpoint) visto che potrebbe crescere esponenzialmente nel tempo

### Comandi utili

Vedere l'ip del container da dentro il container

```shell
 cat /etc/hosts | grep $(hostname) | head -q -c 10 && echo ''
```

Vedere l'ip del container da fuori il container (sostituire `php81_hoqu2` con il nome del container del quale si vuole vedere l'ip)

```shell
docker inspect php81_hoqu2 --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
```

Verificare che Xdebug sia installato da dentro il container phpfpm

```php
php -v
```

dovrebbe essere presente un messaggio simile a questo: `with Xdebug v3.2.0, Copyright (c) 2002-2022, by Derick Rethans`

## Considerazioni su un ipotetico metodo 2: solo 2 container

E' abbastanza chiaro che l'approccio precedente ha dei difetti, ne elenco qualcuno:

- una sola istanza di Xdebug per volta, quindi si può debuggare un progetto per volta
- necessità di entrare nel container php dedicato all'istanza per effettuare un comando php artisan
- due ambienti postgres separati, necessarie due connection distinte per interrogare i 2 database
- se aumentano le istanze Laravella complessità aumenta
- ...

Con un pò di configurazione in più, partendo dalle immagini/compose files di una delle due istanze, sarebbe possibile fare un unico container phpfpm ed un unico container postgres_postgis. Esternamente, sulla macchina host o in altro container, sarebbe comodo un webserver nginx/apache che potrebbe funzionare da reverse proxy, così da non dover usare `php artisan serve`, il webserver dev di php. Tuttavia la configurazione di tale ambiente esula dalla singola istanza, dovrebbe essere una configurazione che lo sviluppatore ha sulla propria macchina e che non interferirebbe con il sistema docker "per istanza". In più necessiterebbe di un know how un pò più ampio.

Quindi, intanto qualche consiglio per rendere il metodo 1 più "snello":

- valutare la creazione di bash/zsh alias/script per entrare nei container phpfpm
- valutare la creazione di bash/zsh alias/script per lanciare psql all'interno dei container postgres
- valutare la creazione di bash/zsh alias/script per lanciare i vari web server php
- valutare la creazione di bash/zsh alias/script per lanciare tinker

eg (dipende dal SO e dal tipo di shell utilizzata):

aggiungere in fondo al file`~/.zshrc`:

```shell
# exec a bash inside hoqu2 container
alias hoqu2='docker exec -it php81_hoqu2 bash'
# exec a php artisan serve command inside the phpfm hoqu container
alias hoqu2_serve='docker exec -it php81_hoqu2 php artisan serve --host 0.0.0.0'
# exec psql inside hoqu2 postgres container
alias hoqu2_psql='docker exec -it postgres_hoqu2 psql -U postgres'
# ...
```
