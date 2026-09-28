# eGreen Basket MarketLink — Comprehensive System Documentation & Design Specifications

**Project Category**: Web Application Development  
**Submission**: Project Deliverables (S.No 54 – 59, 62 – 63)  
**System Name**: eGreen Basket MarketLink  

---

## 1. Problem Definition & Scope

### 1.1 Context
In contemporary urban and peri-urban food distribution, small-to-medium scale farmers encounter severe structural barriers when bringing freshly harvested produce directly to local consumers:
- Traditional supermarkets impose delayed wholesale payment schedules and steep middleman margins.
- Consumers seeking chemical-free, nutrient-dense seasonal food often do not know which farmers are attending neighborhood weekend markets or what inventory will be available upon arrival.
- Farmers frequently suffer food wastage due to unsold harvest carried home at the end of market day.

### 1.2 Proposed System
**MarketLink** bridges this gap through a pre-order marketplace tailored specifically to farmers markets:
1. **Zero Digital Payment Gateway Friction**: All pre-orders are strictly reserved and settled in person upon collection (cash/card at stall), avoiding transaction surcharges.
2. **Harvest-to-Stall Reservation**: Patrons pre-order produce up to a designated harvest cutoff time, allowing farmers to pick exactly what is already reserved.
3. **Transparent Logistics**: Complete map visibility of venues, operating days, collection windows, and authorized family pickup members.

---

## 2. User Roles & Access Control Matrix (RBAC)

The system enforces strict Role-Based Access Control via custom Laravel middleware:

| Feature / Module | Guest / Public | Customer / Patron | Farmer / Grower | System Admin |
| :--- | :---: | :---: | :---: | :---: |
| Browse Markets & Venues | ✅ | ✅ | ✅ | ✅ |
| View Product Catalog & Search | ✅ | ✅ | ✅ | ✅ |
| Interactive Maps & Directions | ✅ | ✅ | ✅ | ✅ |
| AI Assistant Chatbot | ✅ | ✅ | ✅ | ✅ |
| Save Favorites (Products, Farmers, Markets) | ❌ | ✅ | ❌ | ❌ |
| Household / Family Pickup Management | ❌ | ✅ | ❌ | ❌ |
| Add to Cart & Place Pre-Order | ❌ | ✅ | ❌ | ❌ |
| Modify / Cancel Order Before Cutoff | ❌ | ✅ | ❌ | ❌ |
| Submit Rating & Review Post-Pickup | ❌ | ✅ | ❌ | ❌ |
| Product Catalog Management (CRUD) | ❌ | ❌ | ✅ (Approved) | ❌ |
| Weekly Stock & Cutoff Schedules | ❌ | ❌ | ✅ (Approved) | ❌ |
| Accept / Decline / Ready Pre-Orders | ❌ | ❌ | ✅ (Approved) | ❌ |
| Sales Analytics & Revenue Insights | ❌ | ❌ | ✅ (Approved) | ❌ |
| Respond to Customer Reviews | ❌ | ❌ | ✅ (Approved) | ❌ |
| Farmer Approval & Suspension | ❌ | ❌ | ❌ | ✅ |
| Customer Activation / Deactivation | ❌ | ❌ | ❌ | ✅ |
| Markets CRUD & Timings | ❌ | ❌ | ❌ | ✅ |
| Product & Review Moderation | ❌ | ❌ | ❌ | ✅ |
| Platform-wide Financial Reports | ❌ | ❌ | ❌ | ✅ |

---

## 3. Order Lifecycle State Machine

```
      [ Customer Adds to Basket ]
                   │
                   ▼
       [ Customer Places Pre-Order ]
                   │
                   ▼
           Status: PLACED
                   │
        ┌──────────┴──────────┐
        │                     │
 [ Farmer Accepts ]    [ Farmer Declines / Customer Cancels ]
        │                     │
        ▼                     ▼
Status: ACCEPTED      Status: DECLINED / CANCELLED
        │             (Stock auto-restored to product)
        ▼
[ Produce Harvested & Packed ]
        │
        ▼
Status: READY_FOR_PICKUP (Customer notified via in-app & email)
        │
        ▼
[ Customer / Family Member Collects & Pays at Stall ]
        │
        ▼
Status: COMPLETED
        │
        ▼
[ Customer Review & Rating Unlocked ]
```

---

## 4. Database Schema & Data Dictionary

### 4.1 `users`
- `id` (BIGINT, PK, Auto Increment)
- `name` (VARCHAR 255)
- `email` (VARCHAR 255, Unique)
- `password` (VARCHAR 255, Hashed)
- `phone` (VARCHAR 25)
- `address` (TEXT)
- `role` (ENUM: `admin`, `farmer`, `customer`)
- `status` (ENUM: `active`, `suspended`, `inactive`)
- `profile_image` (VARCHAR 255, Nullable)

### 4.2 `farmers`
- `id` (BIGINT, PK, Auto Increment)
- `user_id` (BIGINT, FK -> `users.id`, Cascade)
- `stall_name` (VARCHAR 255)
- `contact_person` (VARCHAR 255)
- `phone` (VARCHAR 25)
- `address` (TEXT)
- `bio` (TEXT, Nullable)
- `operating_days` (JSON: Array of days e.g. `["Saturday", "Sunday"]`)
- `pickup_windows` (VARCHAR 255)
- `order_cutoff_hours` (INT, Default: 12)
- `latitude` (DECIMAL 10,7, Nullable)
- `longitude` (DECIMAL 10,7, Nullable)
- `approval_status` (ENUM: `pending`, `approved`, `suspended`)
- `is_organic` (BOOLEAN, Default: false)

### 4.3 `customers`
- `id` (BIGINT, PK, Auto Increment)
- `user_id` (BIGINT, FK -> `users.id`, Cascade)
- `preferences` (JSON: Stores `preferred_market_ids`, `family_members` with pickup permissions)

### 4.4 `markets`
- `id` (BIGINT, PK, Auto Increment)
- `name` (VARCHAR 255)
- `slug` (VARCHAR 255, Unique)
- `address` (TEXT)
- `city` (VARCHAR 100)
- `operating_days` (JSON)
- `opening_time` (TIME)
- `closing_time` (TIME)
- `latitude` (DECIMAL 10,7)
- `longitude` (DECIMAL 10,7)
- `status` (ENUM: `active`, `inactive`)

### 4.5 `products`
- `id` (BIGINT, PK, Auto Increment)
- `farmer_id` (BIGINT, FK -> `farmers.id`, Cascade)
- `category_id` (BIGINT, FK -> `categories.id`)
- `market_id` (BIGINT, FK -> `markets.id`, Nullable)
- `name` (VARCHAR 255)
- `description` (TEXT, Nullable)
- `price` (DECIMAL 10,2)
- `unit` (VARCHAR 50: `kg`, `bunch`, `dozen`, `piece`, `box`, `lb`, etc.)
- `quantity` (INT, Current Available Stock)
- `default_weekly_quantity` (INT, Recurring template baseline)
- `availability_status` (ENUM: `available`, `low_stock`, `sold_out`)
- `is_organic` (BOOLEAN)

### 4.6 `pickup_slots`
- `id` (BIGINT, PK, Auto Increment)
- `farmer_id` (BIGINT, FK -> `farmers.id`)
- `market_id` (BIGINT, FK -> `markets.id`)
- `pickup_date` (DATE)
- `start_time` (TIME)
- `end_time` (TIME)
- `max_orders` (INT)
- `booked_count` (INT, Default: 0)
- `is_active` (BOOLEAN)

### 4.7 `orders` & `order_items`
- `orders.id` (BIGINT, PK, Auto Increment)
- `orders.order_number` (VARCHAR 50, Unique)
- `orders.customer_id` (BIGINT, FK -> `customers.id`)
- `orders.farmer_id` (BIGINT, FK -> `farmers.id`)
- `orders.market_id` (BIGINT, FK -> `markets.id`)
- `orders.pickup_slot_id` (BIGINT, FK -> `pickup_slots.id`)
- `orders.pickup_date` (DATE)
- `orders.status` (ENUM: `PLACED`, `ACCEPTED`, `DECLINED`, `READY_FOR_PICKUP`, `COMPLETED`, `CANCELLED`)
- `orders.total` (DECIMAL 10,2)
- `orders.notes` (TEXT, Includes notes and `[Pickup By: Name]` if authorized family member designated)
- `order_items.id` (BIGINT, PK, Auto Increment)
- `order_items.order_id` (BIGINT, FK -> `orders.id`, Cascade)
- `order_items.product_id` (BIGINT, FK -> `products.id`)
- `order_items.product_name_snapshot` (VARCHAR 255)
- `order_items.unit_price_snapshot` (DECIMAL 10,2)
- `order_items.unit_snapshot` (VARCHAR 50)
- `order_items.quantity` (INT)
- `order_items.subtotal` (DECIMAL 10,2)

---

## 5. Architectural Assumptions & Constraints

1. **No Online Payment Gateway**: By requirement specification (Constraint #42), all settlements are physical at the farmer's market stall upon produce inspection.
2. **Pickup Only**: No courier or shipping delivery infrastructure is provided (Constraint #43); all fulfillment occurs at designated community market venues during market hours.
3. **No Food Safety Regulatory Verification**: Farmers self-declare organic/standard practices during registration (Constraint #44); platform administrators hold manual approval discretion.
4. **Inventory Locking**: Inventory deductions are protected with pessimistic row locking (`lockForUpdate`) during checkout to prevent overselling on peak market days.

---

## 6. Test Accounts & Credentials Reference

Universal Test Password: **`password`**

```
Admin Account:
- Email: admin@example.com
- Pass : password

Farmer Accounts:
- Email: farmer1@example.com (Green Valley Organics)
- Email: farmer2@example.com (Sunshine Acres)
- Email: farmer3@example.com (Riverside Herb Co.)
- Email: pending@example.com (Emma Newfield - Pending Approval)
- Pass : password

Customer Accounts:
- Email: customer@example.com (Alice Customer)
- Email: bob@example.com (Bob Shopper)
- Pass : password
```
