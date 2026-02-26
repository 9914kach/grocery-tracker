# 🛣 Grocery Tracker – Professional Environment Roadmap

Syfte: Förvandla projektet till en arbetsplats-lik miljö med tydliga processer, CI, kodkvalitet och struktur.

---

# ✅ Milestone 1 – Stabil lokal utvecklingsmiljö

## Mål
Projektet ska kunna startas av vem som helst med 3–5 kommandon.

## Tasks
- [ ] Förbättra README med:
  - Requirements (Docker, Docker Compose)
  - Quick start guide
  - Vanliga kommandon (make up, make fresh osv)
- [ ] Säkerställ att `.env.example` är komplett
- [ ] Rensa hårdkodade container-namn i Makefile
- [ ] Lägg till `make test` kommando
- [ ] Lägg till `make lint` kommando

---

# ✅ Milestone 2 – Kodkvalitet & Standardisering

## Mål
Kodbasen ska följa gemensamma regler automatiskt.

## Backend
- [ ] Installera Laravel Pint
- [ ] Installera PHPStan / Larastan
- [ ] Konfigurera strict level
- [ ] Lägg till lint + typecheck i Makefile

## Frontend
- [ ] Installera ESLint
- [ ] Installera Prettier
- [ ] Konfigurera lint script i package.json

---

# ✅ Milestone 3 – GitHub CI Pipeline

## Mål
Varje push/PR ska testas automatiskt.

## Tasks
- [ ] Skapa `.github/workflows/ci.yml`
- [ ] Kör composer install
- [ ] Kör php artisan test
- [ ] Kör npm ci
- [ ] Kör npm run build
- [ ] Kör lint & typecheck
- [ ] Säkerställ att pipeline failar korrekt

---

# ✅ Milestone 4 – Git Workflow Professional Setup

## Mål
Efterlikna team-arbete.

## Tasks
- [ ] Skapa PR-template
- [ ] Skapa Issue-templates (bug / feature)
- [ ] Aktivera branch protection
- [ ] Kräv godkänd CI för merge
- [ ] Dokumentera branch-struktur (main, dev, feature/*)

---

# ✅ Milestone 5 – Dev Experience Upgrade

## Mål
Projektet ska vara enkelt att hoppa in i på ny maskin.

## Tasks
- [ ] Lägg till `.devcontainer/`
- [ ] Definiera extensions
- [ ] Lägg till postCreateCommand
- [ ] Dokumentera VS Code setup

---

# ✅ Milestone 6 – Miljöer & Struktur

## Mål
Projektet ska vara redo för staging/produktion.

## Tasks
- [ ] Dokumentera environment-struktur
- [ ] Definiera staging-strategi
- [ ] Strukturera config-filer
- [ ] Planera deployment-strategi (framtida)

---

# 🎯 Stretch Goals

- [ ] Docker multi-stage builds
- [ ] Seeders med realistisk testdata
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Monitoring/logging strategi
- [ ] Role/Permission system

---

# 📌 Definition of "Professional Ready"

Projektet är professionellt när:

- CI är grön på alla PR
- Kod formatteras automatiskt
- Statisk analys är aktiverad
- Tester körs automatiskt
- Onboarding tar <10 minuter
- Ingen manuell konfiguration krävs

---

# 🚀 Long-Term Vision

Grocery Tracker ska:
- Vara modulärt uppbyggt
- Vara testbart
- Ha tydlig separation mellan domän & infrastruktur
- Kunna deployas utan manuell handpåläggning
- Spegla hur ett riktigt produktteam arbetar
