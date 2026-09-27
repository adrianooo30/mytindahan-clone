Absolutely. Since you're going to give this to an **AI coding editor**, the most useful format is a **master implementation plan + small sequential prompts**. That prevents the AI from trying to generate the entire application at once and producing a messy codebase.

Below is a plan specifically for **Laravel + React + Tailwind CSS + shadcn/ui + SQLite**.

# MyTindahan Clone — AI Coding Plan

## 1. Project Goal

Build a modern sari-sari store management application inspired by the functionality and UX of MyTindahan.

[MyTindahan](https://www.mytindahan.store/?utm_source=chatgpt.com)

The application should have two major parts:

```text
┌─────────────────────────────────────────────┐
│              PUBLIC WEBSITE                 │
│                                             │
│ Landing page                                 │
│ Features                                     │
│ Pricing                                      │
│ How it works                                 │
│ Testimonials                                 │
│ Login / Register                             │
└─────────────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────┐
│             STORE APPLICATION               │
│                                             │
│ Dashboard                                   │
│ POS / Sales                                 │
│ Products                                    │
│ Inventory                                   │
│ Customers / Utang                            │
│ GCash                                       │
│ E-load                                      │
│ Bills                                       │
│ Employees                                   │
│ Expenses                                    │
│ Reports                                     │
│ Settings                                    │
└─────────────────────────────────────────────┘
```

---

# 2. Technology Stack

Use exactly:

### Backend

* Laravel
* Laravel API / web routing as appropriate
* SQLite
* Laravel Eloquent
* Laravel migrations
* Laravel Form Requests
* Laravel Policies
* Laravel API Resources where useful

### Frontend

* React
* TypeScript
* Tailwind CSS
* shadcn/ui
* Lucide React icons

### Architecture

Prefer:

```text
Laravel
   │
   ├── Authentication
   ├── Authorization
   ├── Business logic
   ├── Database
   └── API
          │
          ▼
       React
          │
          ├── Pages
          ├── Components
          ├── Forms
          ├── Tables
          └── Dashboard
```

Do **not** put business logic inside React components.

---

# 3. Development Rules for the AI Coding Assistant

Give your coding assistant these rules **before starting development**.

# Project Development Rules

You are working on a Laravel + React + TypeScript + Tailwind CSS + shadcn/ui + SQLite application.

## General Rules

1. Do not rewrite existing functionality unless explicitly requested.
2. Before creating a new abstraction, inspect the existing project structure.
3. Reuse existing components whenever possible.
4. Do not duplicate components unnecessarily.
5. Keep components small and focused.
6. Keep business logic out of React presentation components.
7. Keep database logic inside Laravel models/services/repositories where appropriate.
8. Use Laravel Form Requests for validation.
9. Use Laravel Policies for authorization.
10. Use Eloquent relationships instead of manually constructing SQL whenever practical.
11. Use database transactions for operations that modify multiple related records.
12. Never trust calculations sent by the frontend.
13. Calculate totals, prices, inventory changes, and financial values on the backend.
14. Never store passwords or sensitive credentials in plaintext.
15. Do not commit .env files or secrets.
16. Use TypeScript instead of JavaScript for React application code.
17. Avoid `any` unless there is a strong technical reason.
18. Use shadcn/ui components where an appropriate component exists.
19. Use Tailwind CSS for layout and styling.
20. Do not introduce another UI framework.

## Database Rules

Use SQLite during development.

Every schema change must use a Laravel migration.

Do not manually modify the SQLite database schema.

Use foreign keys and appropriate indexes.

Use decimal/integer-safe representations for monetary values.

Do not use floating-point arithmetic for money.

Prefer storing monetary amounts as integer cents when appropriate.

## React Rules

Use reusable components.

Example:

components/
├── ui/
├── layout/
├── dashboard/
├── products/
├── inventory/
├── sales/
├── customers/
└── reports/

Avoid giant components such as:

Dashboard.tsx > 1000 lines

Instead split them into focused components.

## UI Rules

Use shadcn/ui as the primary component system.

Use:

* Button
* Input
* Label
* Card
* Dialog
* Sheet
* DropdownMenu
* Select
* Tabs
* Badge
* Table
* Alert
* Toast/Sonner
* Calendar
* Popover
* Command

Use Lucide icons.

The application should feel like a polished modern SaaS dashboard.

## UX Rules

Every CRUD operation should provide:

* loading state
* success feedback
* validation errors
* empty state
* error state

Destructive actions must require confirmation.

Tables should support:

* search
* filtering where useful
* pagination
* sorting where useful

Forms should have clear validation messages.

The UI must be responsive.

Design mobile-first.

## Code Quality

Before declaring a feature complete:

1. Run the relevant tests.
2. Run Laravel Pint.
3. Run TypeScript checks.
4. Run frontend linting.
5. Fix errors.
6. Verify database migrations.
7. Verify authorization.
8. Verify empty/loading/error states.

Do not claim something works if it has not been tested.

---

# 4. Folder Architecture

Use a structure approximately like:

```text
app/
├── Actions/
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Policies/
├── Services/
└── Support/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
└── js/
    ├── components/
    │   ├── ui/
    │   ├── layout/
    │   ├── dashboard/
    │   ├── products/
    │   ├── inventory/
    │   ├── sales/
    │   ├── customers/
    │   ├── gcash/
    │   ├── bills/
    │   ├── employees/
    │   └── reports/
    │
    ├── layouts/
    ├── pages/
    │   ├── auth/
    │   ├── dashboard/
    │   ├── products/
    │   ├── inventory/
    │   ├── sales/
    │   ├── customers/
    │   ├── gcash/
    │   ├── bills/
    │   ├── employees/
    │   └── reports/
    │
    ├── hooks/
    ├── lib/
    ├── types/
    └── app.tsx

routes/
├── web.php
└── api.php
```

---

# 5. Database Design

This is one of the most important parts.

Don't let the AI start building React screens before designing the database.

## Core tables

```text
users
stores
store_user
products
product_categories
inventory
inventory_batches
suppliers

customers
customer_transactions

sales
sale_items
payments

cash_transactions

gcash_transactions

eload_transactions

bill_payments

employees
employee_payments

expenses

notifications
```

---

# 6. Multi-Store Architecture

Even if the first version only supports one store, design the database so a user can eventually have multiple stores.

```text
users
  │
  └── store_user
          │
          ▼
        stores
```

Most store-specific tables should have:

```text
store_id
```

Example:

```text
products
---------
id
store_id
category_id
name
sku
barcode
description
cost_price
selling_price
reorder_level
is_active
created_at
updated_at
```

This makes the application much easier to expand later.

---

# 7. Products

Product model:

```text
products

id
store_id
category_id

name
sku
barcode
description

cost_price
selling_price

unit
reorder_level

is_active

created_at
updated_at
```

Category:

```text
product_categories

id
store_id
name
description
created_at
updated_at
```

---

# 8. Inventory

Don't simply store:

```text
product.quantity
```

because you eventually want batch tracking.

Use:

```text
inventory_batches

id
store_id
product_id

batch_number

quantity
remaining_quantity

cost_price

manufactured_at
expires_at

created_at
updated_at
```

Then calculate available inventory from batches.

This allows:

```text
Coca-Cola

Batch A
100 units
expires Jan 2027

Batch B
50 units
expires Mar 2027
```

You can then implement:

* low stock alerts
* expiration alerts
* batch tracking
* stock adjustments
* stock receiving

---

# 9. Sales / POS

Core structure:

```text
sales

id
store_id
customer_id nullable

invoice_number

subtotal
discount
tax
total

payment_status

sold_at

created_by
```

Sale items:

```text
sale_items

id
sale_id
product_id

quantity

unit_price
cost_price

discount
subtotal
```

Payments:

```text
payments

id
sale_id

payment_method

amount

reference_number

paid_at
```

Payment methods:

```text
cash
gcash
utang
```

---

# 10. Critical POS Transaction

When a sale occurs:

```text
BEGIN TRANSACTION

Create sale

        ↓

Create sale items

        ↓

Calculate totals

        ↓

Decrease inventory

        ↓

Create payment

        ↓

If UTANG:
    create customer debt transaction

        ↓

If CASH:
    create cash transaction

        ↓

COMMIT
```

If anything fails:

```text
ROLLBACK
```

This is extremely important.

You don't want:

```text
Sale created
Inventory NOT deducted
Payment NOT recorded
```

---

# 11. Utang System

Customer:

```text
customers

id
store_id

name
phone
address

credit_limit

is_active
```

Transactions:

```text
customer_transactions

id
store_id
customer_id
sale_id nullable

type

amount

balance_after

description

transaction_date
```

Types:

```text
debt
payment
adjustment
```

Example:

```text
Juan
──────────────────

Previous balance   ₱500

New purchase       ₱200

Current balance    ₱700
```

When payment is made:

```text
Payment             ₱300

New balance         ₱400
```

---

# 12. GCash

Create a GCash ledger rather than simply storing a balance.

```text
gcash_transactions

id
store_id

type

amount

reference_number

description

transaction_date
```

Types:

```text
cash_in
cash_out
customer_payment
transfer
adjustment
```

Balance can be derived from the transaction history.

---

# 13. E-load

```text
eload_transactions

id
store_id

customer_id nullable

mobile_number

network

product_name

amount

reference_number

status

sold_at
```

Status:

```text
pending
successful
failed
refunded
```

---

# 14. Bills

```text
bill_payments

id
store_id
customer_id nullable

biller

account_number

account_name

amount

service_fee

total

reference_number

status

paid_at
```

---

# 15. Employees

```text
employees

id
store_id

name
phone
position

salary_type
salary_amount

is_active
```

Employee payments:

```text
employee_payments

id
employee_id

amount

payment_date

description
```

Salary types:

```text
daily
weekly
monthly
```

---

# 16. Expenses

```text
expenses

id
store_id

category

description

amount

expense_date

created_by
```

Examples:

```text
Electricity
Rent
Supplies
Transportation
Miscellaneous
```

---

# 17. Dashboard

The dashboard should show:

```text
Today's Sales
₱8,450

Today's Transactions
124

Outstanding Utang
₱23,400

Inventory Value
₱145,000
```

Then:

```text
Sales Overview
────────────────────────

     📈 Sales chart
```

And:

```text
Low Stock
─────────────────────

Coca-Cola       5
Lucky Me        3
Tide            2
```

And:

```text
Expiring Soon
─────────────────────

Product          Expiry

Milk             Oct 3
Bread            Oct 5
```

---

# 18. Product Management UI

Products page:

```text
Products

[ Search products... ] [Category ▼] [+ Add Product]

┌─────────────────────────────────────────────┐
│ Product │ SKU │ Stock │ Price │ Status │ ...│
├─────────────────────────────────────────────┤
│ Coke    │ ... │ 25    │ ₱25   │ Active │ ...│
│ Sprite  │ ... │ 18    │ ₱25   │ Active │ ...│
└─────────────────────────────────────────────┘
```

Add product dialog:

```text
Name
Category
SKU
Barcode
Cost Price
Selling Price
Reorder Level
```

---

# 19. POS Interface

Make POS one of the most polished parts.

Desktop:

```text
┌───────────────────────────────────────────────────────┐
│ POS                                                   │
├──────────────────────────┬────────────────────────────┤
│                          │                            │
│ Search products          │ Cart                       │
│                          │                            │
│ [ Coke ] [ Sprite ]      │ Coke       2 × ₱25        │
│ [ Chips ] [ Bread ]      │ Sprite     1 × ₱25        │
│ [ Milk ] [ Coffee ]      │                            │
│                          │                            │
│                          │ Subtotal       ₱75         │
│                          │ Discount        ₱0         │
│                          │ TOTAL          ₱75         │
│                          │                            │
│                          │ [Cash] [GCash] [Utang]     │
└──────────────────────────┴────────────────────────────┘
```

Mobile:

```text
Products

[ Search ]

Product cards

       ↓

Cart drawer
```

---

# 20. Reports

Implement reports after the core transaction system.

### Sales report

```text
Daily
Weekly
Monthly
Custom range
```

Metrics:

```text
Gross sales
Net sales
Number of transactions
Average transaction
```

### Product report

```text
Best-selling products
Slow-moving products
Profit by product
```

### Financial report

```text
Cash inflow
Cash outflow
Expenses
Utang payments
GCash transactions
```

---

# 21. Authentication

Implement:

```text
Register
Login
Logout
Forgot password
Reset password
```

After login:

```text
/authenticated
       ↓
/dashboard
```

Protect application routes.

---

# 22. Authorization

Create roles:

```text
owner
manager
cashier
employee
```

Example:

```text
Owner
├── Everything
│
Manager
├── Products
├── Inventory
├── Sales
├── Reports
│
Cashier
├── POS
├── Sales
├── Customers
│
Employee
└── Limited access
```

Use Laravel Policies rather than hiding things only in React.

---

# 23. Landing Page

The public page should visually follow the same general product positioning as MyTindahan while using your own implementation/assets.

Sections:

```text
Navbar

Hero

Product screenshot

Features

Inventory feature

Sales/POS feature

Utang feature

GCash feature

E-load feature

Bills feature

Payroll feature

How it works

Testimonials

Pricing

FAQ

CTA

Footer
```

Responsive breakpoints:

```text
mobile
tablet
desktop
large desktop
```

---

# 24. shadcn/ui Component Strategy

Use shadcn components for:

```text
Button
Card
Dialog
Drawer
Sheet
Input
Textarea
Select
Combobox
DropdownMenu
Popover
Calendar
Table
Badge
Tabs
AlertDialog
Tooltip
Skeleton
Separator
Breadcrumb
Command
Pagination
```

Create domain-specific components on top.

For example:

```text
ProductTable
ProductForm
ProductDialog
ProductStatusBadge

SaleTable
SaleDetailsDialog

CustomerTable
CustomerDebtCard
PaymentDialog
```

---

# 25. React State Strategy

Don't put everything into one global store.

Use local state for:

```text
dialogs
forms
filters
temporary UI state
```

Use server state for:

```text
products
sales
customers
inventory
reports
```

Keep the architecture simple initially.

Don't add Redux unless the application genuinely requires it.

---

# 26. API Design

Example:

```text
GET    /api/products
POST   /api/products
GET    /api/products/{product}
PUT    /api/products/{product}
DELETE /api/products/{product}
```

Inventory:

```text
GET  /api/inventory
POST /api/inventory/receive
POST /api/inventory/adjust
```

Sales:

```text
GET  /api/sales
POST /api/sales
GET  /api/sales/{sale}
```

Customers:

```text
GET  /api/customers
POST /api/customers
GET  /api/customers/{customer}
POST /api/customers/{customer}/payments
```

Dashboard:

```text
GET /api/dashboard
```

Reports:

```text
GET /api/reports/sales
GET /api/reports/inventory
GET /api/reports/profit
```

---

# 27. Testing Strategy

Create Laravel feature tests for critical operations.

Especially:

### Sale test

```text
Create product
Create inventory
Create sale
Assert sale exists
Assert sale items exist
Assert inventory decreased
Assert payment exists
```

### Utang test

```text
Create customer
Create sale as utang
Assert customer balance increased
Make payment
Assert balance decreased
```

### Inventory test

```text
Receive inventory
Assert quantity increased

Sell product
Assert quantity decreased

Attempt to sell unavailable quantity
Assert transaction rejected
```

### Authorization test

```text
Cashier cannot access owner-only functionality
```

---

# 28. Development Order

This is the order I'd strongly recommend giving the AI.

```text
PHASE 01
Project setup

PHASE 02
Database architecture

PHASE 03
Authentication

PHASE 04
Application layout

PHASE 05
Products

PHASE 06
Inventory

PHASE 07
POS / Sales

PHASE 08
Customers / Utang

PHASE 09
Cash ledger

PHASE 10
GCash

PHASE 11
E-load

PHASE 12
Bills

PHASE 13
Employees / Payroll

PHASE 14
Expenses

PHASE 15
Dashboard

PHASE 16
Reports

PHASE 17
Landing page

PHASE 18
Notifications / alerts

PHASE 19
Testing

PHASE 20
Polish / responsive design
```

**Do not ask the AI to implement all 20 phases in one prompt.**

---

# 29. Master Prompt

This is the prompt I'd put into your AI coding editor first.

You are the lead software engineer for this project.

We are building a production-quality sari-sari store management SaaS application inspired by the functionality and general UX patterns of MyTindahan.

Technology stack:

* Laravel
* React
* TypeScript
* Tailwind CSS
* shadcn/ui
* SQLite
* Eloquent ORM
* Laravel migrations
* Laravel validation/Form Requests
* Laravel Policies
* Lucide icons

The application will eventually contain:

* Authentication
* Store management
* Products
* Product categories
* Inventory
* Inventory batches
* Suppliers
* POS
* Sales
* Payments
* Customers
* Utang / customer credit
* Cash transactions
* GCash transactions
* E-load
* Bills payment
* Employees
* Payroll
* Expenses
* Dashboard
* Reports
* Notifications
* Settings

The application must be designed so a user can eventually manage multiple stores.

Every store-specific table should therefore use store_id where appropriate.

IMPORTANT:

Do not implement the entire application at once.

Work incrementally.

Before implementing each phase:

1. Inspect the existing project.
2. Explain what files you intend to create/change.
3. Identify potential architectural issues.
4. Implement only the requested phase.
5. Run tests/type checks/linting.
6. Fix errors.
7. Summarize what changed.
8. Do not begin the next phase until instructed.

Architecture requirements:

* Business logic belongs on the Laravel side.
* React is responsible for presentation and user interaction.
* Do not trust financial calculations sent by the frontend.
* Calculate sale totals and inventory changes on the backend.
* Use database transactions for financial/inventory operations.
* Use Laravel Form Requests for validation.
* Use Laravel Policies for authorization.
* Use Eloquent relationships.
* Use migrations for all schema changes.
* Use TypeScript.
* Avoid any.
* Use reusable React components.
* Use shadcn/ui.
* Use Tailwind CSS.
* Use Lucide icons.
* Avoid introducing another UI framework.

Money must never use unsafe floating-point arithmetic.

Critical operations such as creating a sale must be atomic.

For example:

Create sale
→ create sale items
→ calculate totals
→ deduct inventory
→ create payment
→ update customer debt if applicable
→ update cash ledger if applicable
→ commit transaction

If any operation fails, rollback the entire transaction.

The UI must include:

* loading states
* empty states
* error states
* success feedback
* form validation
* confirmation dialogs for destructive actions
* responsive layouts
* accessible controls

Use mobile-first responsive design.

Use shadcn/ui components whenever an appropriate component exists.

Do not copy proprietary source code or assets from other websites.

The visual direction can be inspired by modern SaaS applications and the referenced MyTindahan product, but all implementation code should be original.

Development phases:

1. Project setup
2. Database architecture
3. Authentication
4. Application shell/layout
5. Products
6. Inventory
7. POS/Sales
8. Customers/Utang
9. Cash ledger
10. GCash
11. E-load
12. Bills
13. Employees/Payroll
14. Expenses
15. Dashboard
16. Reports
17. Landing page
18. Notifications
19. Testing
20. UI/UX polish

Start with Phase 1 only.

Do not implement Phase 2 yet.

---

# 30. Then Give the AI This Prompt

After the master prompt, use smaller prompts.

### Phase 1

Implement Phase 1: Project Setup.

First inspect the existing repository.

Do not overwrite an existing project unnecessarily.

Verify:

* Laravel version
* PHP version
* Node version
* package manager
* existing React setup
* existing Tailwind setup
* existing shadcn/ui setup
* existing database configuration

Configure the project for:

* Laravel
* React
* TypeScript
* Tailwind CSS
* shadcn/ui
* SQLite

Create a clean development environment.

Configure SQLite.

Verify that Laravel can connect to SQLite.

Configure the frontend correctly.

Add only the dependencies actually required.

Run:

* Laravel application checks
* frontend build
* TypeScript checks
* linting if configured

Do not implement business features yet.

At the end, provide:

1. Files created/changed
2. Dependencies added
3. Commands used
4. Verification results
5. Any issues discovered

Do not continue to Phase 2.

---

# 31. Phase 2 Prompt

Implement Phase 2: Database Architecture only.

Create the foundational Laravel migrations, models, relationships, factories, and seeders.

Create the following core entities:

users
stores
store_user
product_categories
products
inventory_batches
suppliers
customers
customer_transactions
sales
sale_items
payments
cash_transactions
gcash_transactions
eload_transactions
bill_payments
employees
employee_payments
expenses

Do not build the React UI yet.

Requirements:

* Proper foreign keys
* Appropriate indexes
* nullable relationships where appropriate
* timestamps
* soft deletes only where justified
* store_id for store-specific data
* Eloquent relationships
* factories
* realistic seed data

Use safe monetary storage.

Document the money representation chosen.

Create a DatabaseSeeder that can generate a realistic development store.

Create tests that verify the important relationships.

Run migrations from a clean SQLite database.

Run the test suite.

Do not implement POS business logic yet.

Do not implement dashboard UI.

Do not continue to Phase 3.

---

# 32. Phase 3 Prompt

Then:

Implement Phase 3: Authentication.

Implement:

* registration
* login
* logout
* authenticated user retrieval
* password reset if the current project architecture supports it
* protected application routes

Use Laravel's recommended authentication architecture for the installed Laravel version.

The frontend must contain polished React authentication screens using shadcn/ui.

Create:

* Login page
* Register page
* Forgot password page if supported
* Authentication layout

Add proper validation.

Display backend validation errors in the React forms.

Do not implement product, inventory, POS, or dashboard business functionality yet.

Test:

* registration
* login
* logout
* unauthorized access
* authenticated access

Do not continue to Phase 4.

---

# 33. The Most Important Rule

When you get to **POS**, don't let your AI editor simply create:

```tsx
const total = cart.reduce(...)
```

and then send that total to Laravel.

Instead:

```text
React
  │
  │ product IDs + quantities
  ▼
Laravel
  │
  ├── fetch current products
  ├── verify inventory
  ├── verify prices
  ├── calculate subtotal
  ├── calculate discount
  ├── calculate total
  ├── deduct inventory
  ├── create sale
  ├── create payment
  └── create ledger transaction
```

The **backend is the source of truth**.

That's particularly important for a store/financial application.

---

# 34. Your MVP

I would actually stop after this point initially:

```text
Authentication
      ↓
Dashboard
      ↓
Products
      ↓
Inventory
      ↓
POS
      ↓
Customers
      ↓
Utang
      ↓
Cash transactions
      ↓
Basic reports
```

That gives you a genuinely usable **sari-sari store POS/inventory application** before you spend time implementing GCash, e-load, bills and payroll.

Then add:

```text
GCash
E-load
Bills
Employees
Payroll
Expenses
Advanced reports
Notifications
```

This approach is much safer for AI-assisted development because each feature can be **built → tested → reviewed → committed** before the next feature changes the system.

And given that you're already working with **Laravel + React + Tailwind**, this is a good project for deliberately practicing architecture rather than letting the AI "vibe code" the whole application for you.
