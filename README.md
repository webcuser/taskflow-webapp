# taskflow-webapp

Web app per gestione task e progetti per piccoli team.

## Overview

# Product Requirements Document (PRD)

## 1. Project Overview

**TaskFlow** è una web application progettata per consentire a utenti individuali e piccoli team di organizzare il proprio lavoro tramite la gestione di progetti, task e avanzamento delle attività. L’obiettivo è offrire un’esperienza intuitiva, veloce e responsive, che semplifichi la pianificazione, il monitoraggio e la collaborazione quotidiana, riducendo la complessità operativa tipica degli strumenti enterprise.

---

## 2. Goals & Success Metrics

### Obiettivi

- Consentire la gestione efficace di progetti e task per team fino a 20 utenti.
- Offrire una user experience moderna, semplice e mobile-first.
- Garantire la collaborazione tramite commenti, notifiche e ruoli granulari.
- Fornire strumenti di ricerca e filtro avanzati per una rapida consultazione delle attività.

### Success Metrics

- **Registrazioni utenti**: ≥ 500 utenti attivi nei primi 6 mesi.
- **Tempo medio di caricamento**: < 2 secondi per il 95% delle richieste.
- **Tasso di completamento task**: ≥ 70% dei task creati vengono completati.
- **Engagement**: ≥ 60% degli utenti attivi settimanalmente.
- **Soddisfazione utenti**: CSAT ≥ 4/5 nelle survey post-MVP.
- **Zero incidenti di sicurezza**: nessun data breach o perdita dati.
- **Copertura test**: ≥ 90% di copertura per le API principali.

---

## 3. Target Users

- **Freelancer**: necessità di gestire progetti multipli, tracciare task e scadenze, collaborare con clienti.
- **Piccole aziende**: coordinamento tra membri del team, visibilità su avanzamento lavori, assegnazione task.
- **Startup**: organizzazione rapida, adattabilità, gestione di team dinamici e progetti multipli.
- **Team fino a 20 utenti**: collaborazione, visibilità, notifiche in tempo reale, ruoli differenziati.

**Needs principali:**
- Semplicità d’uso e onboarding rapido.
- Collaborazione e comunicazione immediata.
- Visibilità chiara su priorità, scadenze e responsabilità.
- Sicurezza e affidabilità dei dati.

---

## 4. Core Features

### 4.1 Autenticazione & Profilo Utente

- **Registrazione** (email/password)
- **Login** (Laravel Sanctum, sessione sicura)
- **Recupero password**
- **Gestione profilo** (nome, email, password)
- **Ruoli utente**: admin, member, guest (per progetto)
- **Logout**

### 4.2 Dashboard

- **Task assegnati**: elenco e stato attuale
- **Task completati**
- **Task in ritardo** (scaduti)
- **Attività recenti** (modifiche, commenti, cambi stato)
- **Widget riepilogo**: conteggio task per stato, progetti attivi

### 4.3 Gestione Progetti

- **CRUD progetti**: creazione, modifica, eliminazione, archiviazione
- **Campi progetto**: nome, descrizione, data creazione, owner, stato (attivo/archiviato)
- **Gestione membri**: invito, assegnazione ruolo (admin, member, guest)
- **Ricerca e filtri**: per nome, stato, owner
- **Visualizzazione elenco e dettaglio progetto**

### 4.4 Gestione Task

- **CRUD task**: creazione, modifica, eliminazione
- **Campi task**: titolo, descrizione, priorità, stato, data scadenza, assegnatario
- **Stati task**: To Do, In Progress, Review, Done
- **Kanban board**: drag & drop per cambio stato
- **Ricerca e filtri avanzati**:
    - Testo (titolo, descrizione)
    - Stato
    - Priorità
    - Assegnatario
    - Progetto
    - Data di scadenza
    - Ordinamento per data creazione, scadenza, priorità

### 4.5 Commenti & Cronologia Modifiche

- **Commenti su task**: testo formattato semplice, menzioni @username
- **Notifica all’utente menzionato**
- **Cronologia modifiche**: traccia tutte le modifiche principali (stato, titolo, descrizione, assegnatario, priorità, scadenza, creazione, eliminazione)
    - Per ogni evento: utente, data/ora, valore precedente

### 4.6 Notifiche

- **In-app real-time** (WebSocket, fallback polling)
- **Email** (queue asincrona)
- **Eventi notificati**:
    - Assegnazione task
    - Menzione in commento
    - Cambio stato task assegnato
    - Scadenza imminente (24h prima)
    - Nuovo commento su task assegnato
- **Gestione notifiche**: lettura, marcatura come lette, filtro per tipo/canale

### 4.7 Permessi & Ruoli

- **Admin**: gestione completa progetto, membri, impostazioni, task
- **Member**: può creare/modificare/completare task a cui ha accesso
- **Guest**: sola lettura su progetti/task assegnati
- **Gestione permessi**: policy backend, UI adattiva frontend

### 4.8 Responsive Design

- **Supporto mobile e desktop**
- **UI adattiva**: layout ottimizzato per schermi piccoli e grandi

---

## 5. Technical Architecture

### 5.1 Stack Tecnologico

- **Frontend**: React, TypeScript, Tailwind CSS, Vite
- **Backend**: Laravel 12 (PHP 8.4), REST API
- **Database**: PostgreSQL
- **Cache/Queue**: Redis
- **Storage**: Amazon S3 (o locale)
- **Real-time**: Laravel Reverb, Laravel Echo (WebSocket)
- **Email**: SMTP tramite Laravel Queue

### 5.2 Modello Dati Principale

#### Utente

| Campo       | Tipo      | Note                   |
|-------------|-----------|------------------------|
| id          | int       | PK                     |
| nome        | string    |                        |
| email       | string    | univoco                |
| password    | string    | hashata                |
| created_at  | datetime  |                        |
| updated_at  | datetime  |                        |

#### Progetto

| Campo        | Tipo      | Note                   |
|--------------|-----------|------------------------|
| id           | int       | PK                     |
| nome         | string    |                        |
| descrizione  | text      |                        |
| owner_id     | int       | FK utente              |
| stato        | enum      | attivo, archiviato     |
| created_at   | datetime  |                        |
| updated_at   | datetime  |                        |

#### project_user (join table ruoli)

| Campo      | Tipo      | Note                         |
|------------|-----------|------------------------------|
| id         | int       | PK                           |
| project_id | int       | FK progetto                  |
| user_id    | int       | FK utente                    |
| role       | enum      | admin, member, guest         |
| joined_at  | datetime  |                              |
| created_at | datetime  |                              |
| updated_at | datetime  |                              |

#### Task

| Campo        | Tipo      | Note                   |
|--------------|-----------|------------------------|
| id           | int       | PK                     |
| project_id   | int       | FK progetto            |
| titolo       | string    |                        |
| descrizione  | text      |                        |
| stato        | enum      | todo, in_progress, review, done |
| priorità     | enum      | low, medium, high      |
| assigned_to  | int       | FK utente              |
| due_date     | date      |                        |
| created_at   | datetime  |                        |
| updated_at   | datetime  |                        |

#### Commento

| Campo      | Tipo      | Note                   |
|------------|-----------|------------------------|
| id         | int       | PK                     |
| task_id    | int       | FK task                |
| user_id    | int       | FK utente              |
| testo      | text      |                        |
| created_at | datetime  |                        |
| updated_at | datetime  |                        |

#### Modifica Task (cronologia)

| Campo         | Tipo      | Note                   |
|---------------|-----------|------------------------|
| id            | int       | PK                     |
| task_id       | int       | FK task                |
| user_id       | int       | FK utente              |
| campo         | string    | campo modificato       |
| valore_precedente | text  |                        |
| valore_nuovo      | text  |                        |
| created_at    | datetime  | data/ora modifica      |

#### Notifica

| Campo        | Tipo      | Note                           |
|--------------|-----------|--------------------------------|
| id           | int       | PK                             |
| user_id      | int       | destinatario                   |
| type         | enum      | task_assigned, task_updated, mention, due_date, comment |
| channel      | enum      | in_app, email                  |
| title        | string    |                                |
| message      | text      |                                |
| entity_type  | string    | Task, Project, Comment         |
| entity_id    | int       |                                |
| is_read      | bool      |                                |
| sent_at      | datetime  |                                |
| read_at      | datetime  |                                |
| created_at   | datetime  |                                |
| updated_at   | datetime  |                                |

### 5.3 API Principali

- `POST /login`
- `POST /register`
- `GET /projects`
- `POST /projects`
- `PUT /projects/{id}`
- `DELETE /projects/{id}`
- `GET /tasks`
- `POST /tasks`
- `PUT /tasks/{id}`
- `DELETE /tasks/{id}`
- `GET /tasks/{id}/comments`
- `POST /tasks/{id}/comments`
- `GET /notifications`
- `PUT /notifications/{id}/read`

**Esempio payload e risposta**: vedi sezione Q&A originale.

### 5.4 Componenti Chiave

- **Frontend SPA**: React + Vite, gestione stato con React Query/Redux, autenticazione via JWT/Sanctum.
- **Backend REST API**: Laravel, policy di autorizzazione, validazione, broadcasting eventi.
- **Real-time**: Laravel Reverb + Echo (WebSocket), fallback polling.
- **Email**: invio asincrono tramite queue.
- **Storage**: Amazon S3 per file (futuro), locale per MVP.
- **Backup**: backup giornaliero database.

---

## 6. Non-Functional Requirements

- **Performance**: tempo medio di caricamento < 2s, ottimizzazione query e asset.
- **Sicurezza**:
    - HTTPS obbligatorio
    - Password cifrate (bcrypt/argon2)
    - Autenticazione via Laravel Sanctum
    - Protezione CSRF
    - Rate limiting API
    - Logging e audit trail modifiche
- **Scalabilità**: architettura a servizi separati, supporto a scaling orizzontale backend/frontend.
- **Affidabilità**: backup giornaliero, monitoring errori, test automatici.
- **Compatibilità**: supporto browser moderni, mobile e desktop.
- **Accessibilità**: WCAG AA (minimo), focus su contrasto e navigazione tastiera.
- **Manutenibilità**: codice modulare, documentazione API, CI/CD pipeline.

---

## 7. Out of Scope (v1)

- Allegati nei commenti/task
- Integrazione Google Calendar
- Dashboard statistiche avanzate
- Tag/etichette personalizzate
- Esportazione PDF
- Notifiche push mobile
- API pubbliche
- Campi personalizzati task/progetto
- Query salvate e ricerca full-text
- Integrazione con altri tool esterni

---

## 8. Open Questions

- **Internazionalizzazione**: sarà necessario il supporto multilingua già in v1?
- **Gestione onboarding/inviti**: la gestione inviti utenti su progetti sarà via email o solo tramite link diretto?
- **Limiti storage**: quali limiti di storage per utente/progetto saranno applicati (quando verranno introdotti gli allegati)?
- **Gestione account aziendali**: è previsto un piano multi-azienda o solo multi-progetto?
- **Gestione notifiche email**: sarà possibile per l’utente configurare preferenze di notifica (es. disattivare alcune email)?
- **Accessibilità avanzata**: sono richiesti requisiti di accessibilità superiori allo standard WCAG AA?
- **Data retention**: per quanto tempo saranno conservati i dati di task/progetti eliminati (soft delete, GDPR compliance)?

---

**Questo documento guida la progettazione, l’implementazione e la validazione dell’MVP di TaskFlow. Tutte le specifiche qui riportate sono vincolanti per la realizzazione della v1.**