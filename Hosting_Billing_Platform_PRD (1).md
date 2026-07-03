# Hosting Billing Platform — Product & Engineering Requirements Document

**Version:** 2.1 (Build Status)
**Owner:** Wenslauce
**Target stack:** Laravel 13 / PHP 8.4
**Deployment target:** Self-hosted VPS via Coolify
**Build status:** Phase 0 — Bootstrap ✅ Complete | Phase 1 — Foundation 🏗 In Progress

---

## 1. Vision & Scope

Build a self-hosted WHMCS/Blesta alternative for managing hosting/domain customers end-to-end: order intake, invoicing, payment collection (Stripe + Paystack), automated provisioning on DirectAdmin servers, support tickets, and admin reporting — deployable on your own VPS via Coolify with zero vendor lock-in.

**Non-goals (v1):** multi-currency accounting ledgers, affiliate/reseller programs, WHMCS module marketplace compatibility, multi-language UI. Keep these as Phase 6+ if needed.

---

## 2. Tech Stack

| Layer | Choice | Notes |
|---|---|---|
| Language | PHP 8.4 | Use typed properties, enums, readonly classes |
| Framework | Laravel 12 | |
| DB | MySQL 8 / MariaDB 10.11+ | InnoDB, utf8mb4 |
| Cache/Queue | Redis 7 | Queues, cache, rate limiting, session store |
| Web server | Nginx (via Coolify-managed container) | Local dev: Herd (macOS/Win) |
| Frontend build | Vite + Blade + Alpine.js | Add Livewire for reactive admin/customer UI (recommended — avoids a separate SPA) |
| CSS | Tailwind CSS | |
| Auth | Laravel Fortify/Breeze + Spatie Permission | Roles: super-admin, admin, support, customer |
| Queue driver | Redis + Laravel Horizon | For webhooks, provisioning jobs, emails |
| Search (optional) | Laravel Scout + database driver initially | Upgrade to Meilisearch later if needed |
| Container/runtime | Docker | Built via Coolify's Nixpacks or Dockerfile |
| Deployment | **Coolify** (self-hosted PaaS on your VPS) | Replaces manual GitHub Actions SSH flow |
| VCS | Git + GitHub | Coolify pulls via GitHub App/deploy key |
| Testing | Pest (Laravel default) | Feature tests for every module before merge to `main` |
| API auth | Laravel Sanctum | Token-based for the `/api` module |

---

## 3. High-Level Architecture

```
┌─────────────┐     ┌──────────────┐     ┌────────────────┐
│  Customer    │     │   Admin       │     │   Public API    │
│  Dashboard   │     │   Dashboard   │     │   (Sanctum)     │
│ (Livewire)   │     │  (Livewire)   │     │                 │
└──────┬──────┘     └──────┬───────┘     └────────┬────────┘
       │                    │                       │
       └────────────┬───────┴───────────────────────┘
                     │
              ┌──────▼───────┐
              │  Laravel App  │
              │  (Modules/)   │
              └──────┬───────┘
        ┌────────────┼─────────────────┐
        │            │                 │
  ┌─────▼────┐ ┌─────▼──────┐   ┌──────▼───────┐
  │  MySQL   │ │   Redis     │   │ Integrations  │
  │          │ │ (queue/cache)│  │ Stripe/Paystack│
  └──────────┘ └────────────┘   │ DirectAdmin API │
                                  └────────────────┘
```

**Design principle:** modular monolith. Each business domain lives under `app/Modules/{Name}` with its own models, controllers, services, routes, and Livewire components — not a package-per-module setup (adds unneeded overhead at this stage), but namespaced and testable in isolation.

---

## 4. Modules (Detailed)

### 4.1 Authentication
- Email/password login, email verification, password reset
- 2FA (TOTP) via Fortify for admin accounts — **mandatory for admin/super-admin roles**
- Role-based access via Spatie `laravel-permission` (roles: `super-admin`, `admin`, `support`, `billing`, `customer`)
- Session-based for web, Sanctum tokens for API/customer "API access" feature

### 4.2 Customer Dashboard (Livewire)
- Overview: active services, next invoice due, open tickets
- Service list with status badges (active/suspended/terminated/pending)
- Invoice list + PDF download + "Pay Now"
- Domain list with expiry countdown
- Ticket thread view
- Profile/billing details, saved payment methods

### 4.3 Admin Dashboard (Livewire)
- KPIs: MRR, overdue invoices, tickets open, services expiring in 7/30 days
- Customer management (impersonate customer for support — logged in audit trail)
- Manual invoice/credit note creation
- Product & pricing management
- Server management (DirectAdmin server pool)

### 4.4 Products & Pricing
- Product types: shared hosting, reseller, VPS (metadata only), domain
- Pricing table supports multiple billing cycles (monthly/quarterly/semiannual/annual/biennial) and setup fees
- Product ↔ DirectAdmin package mapping (for auto-provisioning)

### 4.5 Orders → Checkout
- Cart-less, direct "configure product → checkout" flow (simpler than WHMCS cart for v1)
- Order statuses: `pending`, `awaiting_payment`, `paid`, `provisioning`, `active`, `failed`, `cancelled`
- Fraud-light checks: rate limit orders per IP/email, optional manual review flag for first-time customers

### 4.6 Invoices
- Auto-generated on order creation and on renewal (via scheduled command)
- Line items, tax (flat VAT % config, Kenya default 16% — configurable per product/customer)
- States: `draft`, `unpaid`, `paid`, `overdue`, `cancelled`, `refunded`
- PDF generation (dompdf or Browsershot)
- Reminder emails: 7 days before due, on due date, 3/7 days overdue

### 4.7 Payments
- Unified `transactions` table regardless of gateway
- Manual payment recording (bank transfer/cash) for admin
- Automatic invoice marking via gateway webhooks

### 4.8 Stripe Integration
- Stripe Checkout Session for card payments (avoid handling raw card data)
- Webhook: `checkout.session.completed`, `invoice.payment_failed`, `charge.refunded`
- Idempotency keys on all Stripe calls
- Verify webhook signatures using `STRIPE_WEBHOOK_SECRET`

### 4.9 Paystack Integration
- Paystack Inline/Popup or hosted checkout (better for Kenyan M-Pesa via Paystack)
- Webhook: `charge.success`, `refund.processed`
- Verify signature via `x-paystack-signature` header (HMAC SHA512)

### 4.10 DirectAdmin Integration
- Wrapper service `app/Services/Integrations/DirectAdmin/DirectAdminClient.php` using DirectAdmin's HTTP API (basic auth or login key)
- Actions: `createAccount`, `suspendAccount`, `unsuspendAccount`, `terminateAccount`, `changePackage`, `getAccountUsage`
- Jobs run on queue (`ProvisionServiceJob`, `SuspendServiceJob`, etc.) with retry + exponential backoff and admin alert on failure
- Multi-server support: `servers` table stores hostname, API credentials (encrypted via Laravel's `encrypted` cast), max accounts, active flag — provisioning picks least-loaded active server matching the product's allowed server group

### 4.11 Domain Module
- Domain registration/transfer/renewal — v1 scope: **manual tracking only** (store domain, registrar, expiry, auto-renew flag); registrar API (e.g., ResellerClub/Namecheap) integration is a stretch goal, not Phase 5 blocker
- Expiry notification cron (30/14/7/1 day warnings)

### 4.12 Tickets
- Departments (Billing, Technical, Sales)
- Priority, status, SLA fields (optional v1.1)
- Email piping (reply via email → ticket reply) — stretch goal; v1 ships with in-app only
- Canned responses for admin

### 4.13 Email Notifications
- Laravel Notifications (mail channel) queued via Redis
- Templates: welcome, invoice created, payment received, payment failed, service suspended, service terminated, ticket reply, domain expiry
- Use Markdown mail templates for easy branding

### 4.14 Cron Jobs (Laravel Scheduler)
| Task | Frequency |
|---|---|
| Generate renewal invoices | Daily |
| Send invoice due reminders | Daily |
| Mark overdue invoices | Daily |
| Auto-suspend overdue services (grace period configurable) | Daily |
| Domain expiry reminders | Daily |
| Sync DirectAdmin usage stats | Daily |
| Prune old activity logs | Weekly |

Laravel's scheduler needs exactly **one** cron entry on the server:
```
* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1
```
In Coolify, run this via a **scheduled command** resource or a supervisor/cron sidecar in the same container — see §8.

### 4.15 Reports
- Revenue by month/product
- Outstanding balance report
- Churn (terminated/suspended vs active)
- Server utilization (accounts per server)
- Export to CSV

### 4.16 Settings
- Company profile, tax rate, currency, invoice numbering format, payment gateway keys (encrypted at rest), branding (logo/colors), notification toggles

### 4.17 API
- Sanctum-authenticated REST API (`/api/v1/...`)
- Resources: customers, invoices, services, tickets (read + limited write)
- Rate limiting via Laravel's built-in throttle middleware
- OpenAPI spec generated via `dedoc/scramble` or hand-maintained in `/docs/openapi.yaml`

### 4.18 Audit Logs
- Use `spatie/laravel-activitylog`
- Log: admin impersonation, manual payment entries, service suspend/terminate, settings changes, permission changes
- Immutable — no delete UI, only retention pruning via cron

---

## 5. Database Schema (Initial, with key fields)

```
users
  id, name, email, password, role_id (nullable, admins), two_factor_secret,
  email_verified_at, timestamps

roles                      -- via spatie/laravel-permission (roles + permissions tables)
permissions
model_has_roles
model_has_permissions

customers
  id, user_id (FK users), company_name, phone, country, tax_id,
  billing_address_line1/2, city, state, postal_code, status (active/locked),
  timestamps

products
  id, name, slug, type (enum: shared_hosting, reseller, vps, domain),
  description, directadmin_package (nullable string), is_active, timestamps

pricing
  id, product_id (FK), billing_cycle (enum: monthly/quarterly/semiannual/annual/biennial),
  price, setup_fee, currency, timestamps

servers
  id, name, hostname, api_username, api_key (encrypted), server_group,
  max_accounts, current_accounts, is_active, timestamps

orders
  id, customer_id (FK), status (enum), total, currency, notes, timestamps

order_items
  id, order_id (FK), product_id (FK), pricing_id (FK), quantity, unit_price, timestamps

services
  id, order_item_id (FK), customer_id (FK), product_id (FK), server_id (FK, nullable),
  username (DirectAdmin account username), domain, status (enum: pending/active/suspended/terminated),
  next_due_date, billing_cycle, timestamps

domains
  id, customer_id (FK), service_id (nullable FK), domain_name, registrar,
  registered_at, expires_at, auto_renew (bool), status, timestamps

invoices
  id, customer_id (FK), order_id (nullable FK), invoice_number (unique),
  status (enum), subtotal, tax, total, currency, due_date, paid_at, timestamps

invoice_items
  id, invoice_id (FK), service_id (nullable FK), description, quantity, unit_price, total

transactions
  id, invoice_id (FK), gateway (enum: stripe/paystack/manual/bank), gateway_reference,
  amount, currency, status (enum: pending/succeeded/failed/refunded), raw_payload (json), timestamps

payment_methods
  id, customer_id (FK), gateway, token/reference, brand, last4, is_default, timestamps

tickets
  id, customer_id (FK), department, subject, status (enum: open/answered/customer_reply/closed),
  priority (enum: low/medium/high), timestamps

ticket_replies
  id, ticket_id (FK), user_id (nullable, admin), customer_id (nullable), message, is_admin_reply (bool), timestamps

activity_logs                -- provided by spatie/laravel-activitylog package
settings
  id, key (unique), value (json), timestamps
```

**Indexing notes:** add indexes on `invoices.status`, `services.next_due_date`, `services.status`, `domains.expires_at`, `orders.status`, and a unique composite on `(customer_id, domain_name)` for domains.

---

## 6. Folder Structure

```
app/
  Console/Commands/
  Models/
  Modules/
    Auth/
    Customers/
    Products/
    Orders/
    Invoices/
    Payments/
    Tickets/
    Domains/
    Reports/
    Settings/
    Api/
      Http/Controllers/
      Resources/
  Services/
    Billing/
      InvoiceGenerator.php
      RenewalService.php
    Integrations/
      DirectAdmin/
        DirectAdminClient.php
        Actions/ (CreateAccount.php, SuspendAccount.php, ...)
      Stripe/
        StripeClient.php
        WebhookHandler.php
      Paystack/
        PaystackClient.php
        WebhookHandler.php
  Jobs/
    ProvisionServiceJob.php
    SuspendServiceJob.php
    UnsuspendServiceJob.php
    TerminateServiceJob.php
    GenerateRenewalInvoicesJob.php
  Notifications/
  Policies/
  Http/Middleware/
resources/
  views/livewire/
  views/emails/
  css/ js/
routes/
  web.php
  admin.php
  customer.php
  api.php
  console.php
database/
  migrations/
  seeders/
  factories/
tests/
  Feature/
  Unit/
docs/
  openapi.yaml
  ARCHITECTURE.md
```

---

## 7. External APIs

| Service | Purpose | Docs |
|---|---|---|
| Stripe | Card payments | stripe.com/docs |
| Paystack | Card + M-Pesa payments (Kenya) | paystack.com/docs |
| DirectAdmin | Hosting account provisioning | docs.directadmin.com/directadmin/api-all/introduction.html |

Store all API secrets in `.env`, never in code or DB unencrypted. For DirectAdmin per-server credentials stored in DB, use Laravel's `encrypted` Eloquent cast (backed by `APP_KEY`).

---

## 8. Deployment — Coolify (replaces manual SSH/Actions flow)

Since you already have **Coolify** running on your VPS, skip the raw GitHub Actions + `ssh` deploy script from the original draft. Use this instead:

### 8.1 Setup
1. In Coolify: **New Resource → Application → Public/Private Git Repository**, connect your GitHub repo (via GitHub App integration for auto-deploy on push).
2. Build pack: choose **Nixpacks** (auto-detects Laravel) or supply a custom `Dockerfile` (recommended once the app stabilizes, for full control over PHP extensions like `intl`, `gd`, `redis`).
3. Add a **MySQL** resource and a **Redis** resource in Coolify (same project) — Coolify wires up internal networking automatically; use the internal hostnames it generates in your `.env`.
4. Set environment variables in Coolify's UI (mirrors `.env` — see §9). Mark secrets as "sensitive" in Coolify.
5. Set the **Start Command** / entrypoint to run migrations safely on deploy:
   ```
   php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php-fpm
   ```
   (Or use a `post-deployment` command hook in Coolify if you don't want migrations bundled into the container start.)
6. **Persistent storage:** mount a volume for `storage/app` (invoice PDFs, uploads) so it survives redeploys.

### 8.2 Scheduler & Queue workers
Coolify containers don't get a host crontab automatically, so:
- Add a **second Coolify resource** (or a sidecar service in the same `docker-compose`) running `php artisan schedule:work` as a long-lived process — simpler than fighting cron inside the container.
- Add a **third resource** running `php artisan queue:work redis --tries=3 --backoff=30` (or deploy Horizon and run `php artisan horizon`). Use Coolify's health check / auto-restart so queue workers self-heal.

### 8.3 Zero-downtime
- Coolify supports rolling deploys if you run more than one replica — for a billing platform, 1 replica + fast deploys is fine initially.
- Always run `php artisan down --render="maintenance"` → migrate → `php artisan up` for schema changes with data risk, via a Coolify pre/post-deploy command hook.

### 8.4 Backups
- Configure Coolify's built-in **scheduled database backups** for MySQL to S3-compatible storage (Backblaze B2 or similar) — do this before go-live, not after.
- Snapshot the storage volume (invoice PDFs) on the same schedule.

### 8.5 SSL/Domains
- Coolify auto-issues Let's Encrypt certs per domain you attach (e.g., `billing.yourdomain.com`, `api.billing.yourdomain.com` if you split API).

---

## 9. Environment Variables (`.env` reference)

```
APP_NAME="Hosting Billing"
APP_ENV=production
APP_KEY=
APP_URL=https://billing.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=<coolify-internal-mysql-host>
DB_DATABASE=hosting_billing
DB_USERNAME=
DB_PASSWORD=

REDIS_HOST=<coolify-internal-redis-host>
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_FROM_ADDRESS=

STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=

DIRECTADMIN_DEFAULT_TIMEOUT=15
# per-server creds live in DB (encrypted), not here

APP_VAT_RATE=16
DEFAULT_CURRENCY=KES
```

---

## 10. Security Checklist

- [ ] 2FA enforced for all admin/super-admin roles
- [ ] All webhook endpoints verify signatures (Stripe + Paystack) before processing
- [ ] DirectAdmin/gateway credentials encrypted at rest, never logged
- [ ] Rate limiting on login, checkout, and API routes
- [ ] CSRF protection on all web forms (default Laravel)
- [ ] Audit log on impersonation, refunds, permission changes, credential edits
- [ ] Regular `composer audit` / Dependabot enabled on GitHub repo
- [ ] Backups tested with an actual restore drill before go-live
- [ ] `.env` never committed; Coolify secrets marked sensitive

---

## 11. Testing Strategy

- **Pest** feature tests per module (target: every controller/Livewire action has at least a happy-path + one failure-path test)
- Webhook handlers tested with recorded fixture payloads (Stripe/Paystack sample JSON)
- DirectAdmin client tested against a fake/mock HTTP client (Laravel's `Http::fake()`) — no hitting real servers in CI
- CI (GitHub Actions, **for testing only**, not deployment) runs: `composer install`, `php artisan test`, `phpstan` (larastan), `pint --test`
- Coolify only deploys after the GitHub Actions test workflow passes (branch protection rule on `main`)

---

## 12. Roadmap (Sprint-Level)

### Phase 0 — Project Bootstrap (2–3 days)
- `laravel new` (PHP 8.4), install Breeze/Fortify + Livewire + Tailwind
- Install Spatie permission + activitylog
- Set up repo, `develop`/`main` branches, GitHub Actions test workflow
- Connect repo to Coolify, get a "Hello World" deploy live end-to-end first — **do this before writing business logic** so deployment is never a late surprise

### Phase 1 — Foundation (1 week)
- Auth (login, 2FA, roles)
- Admin layout + Customer layout (Livewire shells, nav, permissions gating)
- Base migrations: users, customers, settings, activity_logs

### Phase 2 — Commerce Core (1.5–2 weeks)
- Products + pricing CRUD (admin)
- Order/checkout flow (no payment yet — order lands as `awaiting_payment`)
- Invoice generation + PDF + numbering
- Manual "mark as paid" for admin (unblocks testing before gateways are wired)

### Phase 3 — Payments (1 week)
- Stripe Checkout + webhook handler
- Paystack Checkout + webhook handler
- Transaction ledger, invoice auto-marking, payment failure emails

### Phase 4 — Provisioning (1.5 weeks)
- Server pool CRUD
- DirectAdmin client + provision/suspend/unsuspend/terminate jobs
- Wire order-paid → provisioning job → service activation
- Failure alerting (admin notification if provisioning job fails after retries)

### Phase 5 — Domains, Support, Reports (1.5 weeks)
- Domain tracking + expiry cron
- Tickets (departments, replies, admin canned responses)
- Reports (revenue, outstanding, churn) + CSV export

### Phase 6 — Hardening & Launch (1 week)
- Security checklist pass
- Backup/restore drill on Coolify
- Load-test checkout + webhook endpoints
- Write `docs/ARCHITECTURE.md` and API docs
- Go live

---

## 13. Git Workflow

```
main     = production (Coolify auto-deploys on merge, after CI passes)
develop  = integration branch
feature/* = new features, PR into develop
hotfix/* = urgent prod fixes, PR into main + develop
```

Commit convention (Conventional Commits, recommended for changelog automation):
```
feat(invoices): add PDF generation
fix(stripe): handle webhook signature mismatch
chore(deps): bump laravel/framework
```

---

## 14. First Milestones (Cursor build checklist)

1. [x] `laravel new hosting-billing` on PHP 8.4, push to GitHub
2. [x] Connect repo → Coolify, confirm live deploy of default Laravel welcome page
3. [x] Install Breeze (Livewire stack) + Spatie permission + activitylog
4. [ ] Migrations: users, customers, roles/permissions, settings
5. [ ] Admin layout + Customer layout shells with role-gated nav
6. [ ] Products + Pricing CRUD
7. [ ] Order → Invoice flow (manual payment first)
8. [ ] Stripe Checkout + webhook
9. [ ] Paystack Checkout + webhook
10. [ ] DirectAdmin client + provisioning job wired to "order paid" event
11. [ ] Scheduler + queue worker resources running on Coolify
12. [ ] Backups configured on Coolify
13. [ ] Security checklist signed off
14. [ ] Go live

---

## 15. Open Decisions (flag before Phase 2)

- Confirm whether checkout needs a "cart" (multiple products in one order) or single-product-per-order is acceptable for v1 — recommend single-product for speed, revisit later.
- Confirm VAT handling: flat 16% always, or per-customer tax exemption flag needed at launch?
- Confirm whether domain registrar API integration is required for launch or can stay manual-tracking-only (recommended: manual for v1).
- Confirm number of DirectAdmin servers at launch (affects whether "server pool" logic needs to handle load-balancing on day one or can default to a single server).
