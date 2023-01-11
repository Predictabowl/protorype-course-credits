# Conversione Crediti Universitari
## Breve descrizione
Applicazione full stack scritta in PHP per la conversione dei crediti universitari automatizzata per il passaggio ad uno dei corsi di laurea supportati.

Scritta su framework <a href="https://laravel.com/">Laravel</a>.

## Installazione

### Componenti Necessarie
Essendo un LAMP, per l'installazione è necessario:
- Un'istanza di Linux Server (e.g. <a href="https://ubuntu.com/server">Ubuntu Server</a>), preferibilmente su macchina virtuale
- Un web server (e.g. <a  href="https://httpd.apache.org/">Apache</a>)
- Un istanza di database relazionale, preferibilmente <a href="https://www.postgresql.org/">PostgreSQL</a>, è possibile utilizzare direttamente un'immagine di <a href="https://hub.docker.com/_/postgres/">postgres Docker</a>. Alternativamente è possibile utilizzare anche <a href="https://www.mysql.com/">MySQL</a>
- PHP 8.2
- Un indirizzo di posta elettronica che verrà utilizzato per l'autenticazione degli utenti che si vogliono registrare.

Questo documento presuppone la conoscenza su come configurare Linux, web server e del database scelto.

### Installare applicazione
Clonare il presente repository tramite git
```
git clone https://github.com/Predictabowl/protorype-course-credits.git
```
All'interno della directory clonata installare le dipendenze necessarie tramite <a href="https://getcomposer.org/">composer</a>.
```
composer install --no-dev
```
Copiare il file all'interno della directory ``.env.example`` come ``.env``
```
cp .env.example .env
```
Modificare il file appena creato  ``.env``, configurandolo i dati necessari per connettersi al database installato. ``DB_DATABASE`` con il nome del database, ``DB_USERNAME`` e ``DB_PASSWORD`` con le credenziali d'accesso.
Sempre sul file ``.env`` impostare i dati per poter accedere al server di posta elettronica, tali variabili iniziano tutte con ``MAIL_**``.
Generare la chiave di crittografia dalla directory principale tramite il comando
```
php artisan key:generate
```
Effettuare la migrazione del database con il comando (il database deve essere già in funzione)
```
php artisan migrate:fresh --seed
```
La migrazione installerà automaticamente i dati per i corsi "Scienze dell'Amministazione Digitale" e "Diritto Agroalimentare".

L'applicazione dovrebbe essere pronta al deploy sul web server, se tutto è stato configurato correttamente è possibile provarne il funzionamento in locale con il comando
```
php artisan serve
```
e visitare sul browser a `localhost:8000`.

### Amministratore Base
Prima di rendere Opzionalmente è possibile aggiungere un utente amministratore base con il comando
```
php artisan db:seed --class=DatabaseSeederAdminUser
```
questo creerà un utente con diritti da amministratore con email: `admin@email.org` e password: `password`. Non è consigliato mantenere tale utente in quanto la mail è fittizia e non è quindi possibile cambiare la password dall'applicazione, è più indicato utilizzarlo per conferire diritti da amministrato ad un'altro utente registrato per poi farlo eliminare dal nuovo amministratore appena appuntato.
