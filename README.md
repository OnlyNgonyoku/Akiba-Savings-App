# Akiba Savings App

**Akiba** (Swahili for "savings") is a full‑stack digital savings and coordination platform designed around how people in Kenya manage money—individually, in groups *(chamas)*, and through informal fundraising *(harambees)*. The platform combines a **wallet system**, **goal‑based saving**, and **structured group finance** into one transparent, auditable ecosystem.


---

## Key Features

- **Double‑entry ledger** – Every financial movement is recorded as immutable debit/credit pairs, ensuring perfect auditability.
- **Polymorphic wallets** – Users, groups, goals, fundraisers, and the platform itself each hold one or more wallets.
- **Group savings (Chamas)** – Rotational, milestone‑based, and open‑contributions groups with member roles and payout rules.
- **Personal & group goals** – Lock‑up savings targets with dedicated escrow wallets and progress tracking.
- **Public fundraisers (Harambee)** – Campaigns with deadlines, contributed directly from personal wallets.
- **Withdrawal requests** – Manual approval workflow via the admin panel, with full logging.
- **Mobile‑ready REST API** – Phone‑based OTP authentication, Sanctum tokens, and idempotency‑safe endpoints.
- **Admin dashboard** – Real‑time overview of users, wallets, transactions, pending actions, and financial trends.

---

## Technology Stack

| Layer          | Technology                                    |
|----------------|-----------------------------------------------|
| Backend        | Laravel 12 (PHP 8.3+)                         |
| Admin Panel    | Filament v4 (TALL stack)                      |
| Database       | MySQL 8 (InnoDB)                             |
| Caching/Queue  | Redis                                        |
| Real‑time      | Laravel Reverb / Pusher (WebSockets)          |
| Payments       | Paystack (M‑Pesa integration)                 |
| Frontend (Web) | Livewire + Alpine.js + Tailwind CSS           |
| Mobile API     | Sanctum token authentication, planned mobile app |

---


The Laravel backend enforces all business rules (double‑entry, group cycles, idempotency) and exposes a REST API for mobile clients. The Filament admin panel gives platform operators complete visibility and control.

---

## Database Schema

The database is designed around a **double‑entry ledger** with polymorphic wallets. Every financial event creates a `Transaction` with one or more `LedgerEntries` (debits = credits). Balances are derived from the ledger, never stored as the sole source of truth.

### Core Tables

| Table                | Description                                      |
|----------------------|--------------------------------------------------|
| `users`              | Members (phone‑based auth, optional email)       |
| `wallets`            | Polymorphic balance holders (User, Group, Goal, Fundraiser, System) |
| `groups`             | Chamas / saving groups                           |
| `group_members`      | Pivot with role, position, and join date         |
| `goals`              | Personal or group savings targets                |
| `fundraisers`        | Harambee campaigns                               |
| `transactions`       | High‑level financial events (deposit, contribution, payout…) |
| `ledger_entries`     | Immutable double‑entry rows (debit/credit per wallet) |
| `withdrawal_requests`| Outbound payment requests (approval workflow)    |
| `audit_logs`         | Activity trail for all models                    |

All money columns use `decimal(20,2)`. Refer to the migrations in `database/migrations/` for the full schema.

---

## Admin Panel (Filament)

The web application is built with **Filament v4** and provides:

### Resources
- **UserResource** – Manage users, send OTP, verify KYC.
- **WalletResource** – View all wallets and their computed balances.
- **GroupResource** – Manage chamas, members, and rotation schedules.
- **GoalResource** – Track personal and group saving goals.
- **FundraiserResource** – Oversee public fundraising campaigns.
- **TransactionResource** – Read‑only view of all financial events with embedded ledger entries.
- **LedgerEntryResource** – Immutable ledger rows for audits.
- **WithdrawalRequestResource** – Approval / rejection workflow.
- **AuditLogResource** – Model change history.

### Dashboard Widgets
- **8 live stats** (active users, total deposits, contributions, pending withdrawals, group balances, goal progress, weekly transactions).
- **Pending Withdrawals table** with approve/reject actions.
- **Recent Transactions table** (last 5 completed).
- **Deposits vs Payouts line chart** (30‑day trend).
- **Group Contributions bar chart** (most active chamas).
- **Goal Status doughnut chart** (active/completed/cancelled).

---

## REST API

The API follows RESTful conventions, prefixed with `/api/v1/`. All endpoints (except auth) require a `Bearer` token obtained via phone OTP.

| Endpoint                              | Description                     |
|---------------------------------------|---------------------------------|
| `POST /api/v1/auth/send-otp`          | Request OTP                     |
| `POST /api/v1/auth/verify-otp`        | Verify OTP & get token          |
| `GET /api/v1/wallet`                  | Personal wallet & balance       |
| `GET /api/v1/goals`                   | List user goals                 |
| `POST /api/v1/goals`                  | Create a goal                   |
| `POST /api/v1/goals/{id}/deposit`     | Deposit into goal               |
| `GET /api/v1/groups`                  | List user’s groups              |
| `POST /api/v1/groups/{id}/contribute` | Contribute to a group           |
| `POST /api/v1/withdrawals`            | Request withdrawal              |
| …                                     |                                 |

All payment‑related endpoints require an `Idempotency-Key` header. API documentation will be auto‑generated (OpenAPI/Swagger) in future phases.

---

## Installation & Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySQL 8
- Node.js & npm (for front‑end assets)
- Redis (optional, used for caching / queues)
