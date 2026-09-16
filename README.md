# 🛍️ Nexus Full-Stack E-Commerce Platform

A production-ready, full-stack E-Commerce store built with **Node.js, Express, native SQLite, and modern Vanilla CSS/JS**. Engineered for ultra-fast performance, mobile responsiveness (prioritizing Android devices), payment gateway integrations (Razorpay, Stripe, and Sandbox Simulator), and a complete **Admin Management Suite**.

---

## 🌟 Key Highlights

- **Storefront**: High-converting Home page, category filters, live product search, interactive product details with photo gallery, customer reviews, discount tags, and stock indicators.
- **Cart & Checkout**: Multi-step checkout, coupon discount engine (`WELCOME10`, `FLAT500`), free shipping progress bar, address manager, and itemized invoice receipt.
- **Payment Architecture**: Dual real-gateway integration (**Razorpay** & **Stripe**) + built-in **Instant Sandbox Simulator** for 1-click testing without API keys.
- **Admin Management Suite**:
  - Protected with role-based JWT authentication (`role === 'admin'`).
  - Sales metrics: Gross revenue, total orders, customers, and low-stock indicators.
  - Products Inventory CRUD with live image upload (`/api/admin/upload`).
  - Orders fulfillment & status dropdown updater (`Pending` ➔ `Processing` ➔ `Shipped` ➔ `Delivered`).
  - Category manager and promotional coupon generator.
  - Customer directory with lifetime spend metrics.
- **Database**: Zero-dependency embedded database via Node.js native SQLite engine (`node:sqlite`). Automatic table creation and sample product seeding.
- **Security & SEO**: Input sanitization, bcrypt password hashing, rate limiting, Helmet security headers, `robots.txt`, dynamic `sitemap.xml`, Open Graph tags, and custom SVG brand favicon.

---

## 🚀 1. How to Run Locally

### Prerequisites
- **Node.js v20+** or **Node.js v24+** (Recommended)
- **npm v10+**

### Steps
1. Open your terminal in the project directory:
   ```bash
   cd "c:\Users\akash\Documents\Phela Project"
   ```
2. Install dependencies (if not already done):
   ```bash
   npm install
   ```
3. Start the application:
   ```bash
   npm start
   ```
   *Alternatively, on Windows, simply double-click `run.bat`.*

4. Access the application in your browser:
   - **Storefront**: [http://localhost:3000](http://localhost:3000)
   - **Admin Dashboard**: [http://localhost:3000/admin/index.html](http://localhost:3000/admin/index.html)

---

## 🔑 Default Login Credentials

| Role | Email | Password | Access |
| :--- | :--- | :--- | :--- |
| **Store Admin** | `admin@store.com` | `Admin@12345` | Full Admin Suite (`/admin/*`) |
| **Demo Customer** | `customer@store.com` | `Customer@12345` | Storefront & Order History |

*(Both accounts are equipped with 1-click autofill buttons on `/login.html` for easy testing).*

---

## 🗄️ 2. Creating & Resetting the Database

### Live Proofs and Gameplay on Vercel

The public Proofs and Gameplay pages and the admin gallery now share `data/gallery.json`.
Existing media URLs are included there, so the 8 proofs and 3 gameplay videos appear
in the admin gallery after deployment.

For edits and deletes to persist on Vercel, add these **Production** environment
variables to the Vercel project:

- `GALLERY_GITHUB_REPO=akashgupta9904/Akash-e`
- `GALLERY_GITHUB_BRANCH=main`
- `GALLERY_GITHUB_TOKEN`: a fine-grained GitHub token with **Contents: Read and write**
  access to this repository only
- `GALLERY_EDIT_SECRET`: a long, random secret that you enter in the admin gallery
  when saving or deleting media

Redeploy after adding the variables. A gallery edit commits `data/gallery.json` to
GitHub, and the Vercel Git integration deploys the updated version. Public gallery
requests read the latest file from GitHub, so changes appear before the next build
finishes. Use HTTPS media URLs; Vercel's local file storage is temporary, so the
admin gallery accepts URLs instead of file uploads. Without the two secrets, the
existing media is visible but live editing returns a setup error.

The database is powered by **Node.js native SQLite** and is stored locally at `data/ecommerce.db`.

- **Auto-Initialization**: When you start the server (`node server.js`), the database schema, foreign key constraints, indexes, sample categories, 8 premium sample products, and coupons are automatically created.
- **Resetting Database**: To completely reset the database back to clean demo data:
  1. Stop the server (`Ctrl + C`).
  2. Delete the file `data/ecommerce.db`.
  3. Start the server (`npm start`). It will automatically recreate and re-seed everything fresh.

---

## 💳 3. Adding Payment Gateway Credentials

Open your `.env` file in the root directory and insert your gateway keys:

```env
# Razorpay Credentials (from dashboard.razorpay.com)
RAZORPAY_KEY_ID=rzp_test_your_key_id
RAZORPAY_KEY_SECRET=your_razorpay_secret_key

# Stripe Credentials (from dashboard.stripe.com)
STRIPE_PUBLISHABLE_KEY=pk_test_your_stripe_key
STRIPE_SECRET_KEY=sk_test_your_stripe_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

> [!NOTE]
> If you do not enter gateway keys, the store automatically falls back to the **Instant Test Simulator**, allowing you to test order placement, stock deduction, and order confirmation without any external accounts!

---

## 🧪 4. Testing Payments

1. Open [http://localhost:3000](http://localhost:3000) and add any product to your cart.
2. Go to the cart, enter coupon `WELCOME10` to apply a 10% discount, and click **Proceed to Checkout**.
3. Fill in the shipping details (or log in as `customer@store.com` to auto-fill).
4. Under **Select Payment Method**:
   - Choose **Instant Test Simulator** and click **Complete & Pay**.
   - Your order will instantly be confirmed with payment verified!
5. You will be redirected to the order receipt page (`/order-success.html?orderNumber=...`).
6. Visit the **Admin Dashboard** ([http://localhost:3000/admin/index.html](http://localhost:3000/admin/index.html)), open **Customer Orders**, and you will see your new order listed as `Paid`!

---

## ☁️ 5. Public Deployment

### Option A: 1-Click Cloud Hosting on Render
1. Push this repository to your GitHub account (`git push origin main`).
2. Go to [render.com](https://render.com) and click **New +** ➔ **Web Service**.
3. Select your repository.
4. Render will automatically detect `render.yaml` or set:
   - **Environment**: `Node`
   - **Build Command**: `npm install`
   - **Start Command**: `node server.js`
5. In **Environment Variables**, add:
   - `NODE_ENV`: `production`
   - `JWT_SECRET`: (Click generate random string)
6. Click **Deploy Web Service**! Your site is live with HTTPS within 2 minutes.

### Option B: Docker Container Deployment
Run with Docker anywhere (Railway, AWS ECS, DigitalOcean, VPS):
```bash
docker build -t nexus-store .
docker run -d -p 3000:3000 -v $(pwd)/data:/app/data --name nexus nexus-store
```

---

## 🌐 6. Connecting Your Custom Domain

1. In your domain registrar (GoDaddy, Namecheap, Cloudflare):
   - Add a **CNAME record**:
     - **Host**: `www` or `@`
     - **Target**: Your cloud hosting URL (e.g. `nexus-store.onrender.com`).
   - Or add an **A record** pointing to your server's public IP address.
2. In your cloud provider dashboard (e.g., Render, Railway, Vercel), click **Settings** ➔ **Custom Domains** and add your domain (e.g., `store.yourdomain.com`).
3. SSL certificates (HTTPS) are automatically provisioned via Let's Encrypt at zero cost.

---

## 🔄 7. Switching Payment Gateways from TEST to LIVE Mode

### For Razorpay:
1. Log in to [Razorpay Dashboard](https://dashboard.razorpay.com).
2. Toggle the switch in the top header from **Test Mode** to **Live Mode**.
3. Go to **Settings** ➔ **API Keys** and generate a new key pair (`rzp_live_...`).
4. Update your production `.env` variables:
   ```env
   RAZORPAY_KEY_ID=rzp_live_xxxxxxxxxx
   RAZORPAY_KEY_SECRET=your_live_secret
   ```
5. Restart the server.

### For Stripe:
1. Log in to [Stripe Dashboard](https://dashboard.stripe.com).
2. Switch from **Test Mode** to **Live Mode**.
3. Go to **Developers** ➔ **API keys** and copy your `pk_live_...` and `sk_live_...`.
4. Update your production `.env` variables.
5. Restart the server.

---

## 📁 Project Architecture

```
Phela Project/
├── server.js               # Express server entry point & security middleware
├── package.json            # Node.js dependencies
├── .env.example            # Environment variables template
├── .env                    # Local environment config
├── Dockerfile              # Docker production container
├── render.yaml             # Render 1-click cloud blueprint
├── run.bat                 # Windows 1-click execution script
├── data/
│   └── ecommerce.db        # SQLite database (auto-created & seeded)
├── src/
│   ├── config/
│   │   ├── db.js           # Database engine & SQL schema
│   │   └── seed.js         # Default categories, products & admin user
│   ├── middleware/
│   │   ├── auth.js         # JWT verification & requireAdmin guard
│   │   └── validate.js     # Input sanitizers & validators
│   └── routes/
│       ├── auth.routes.js     # User registration, login, profile, addresses
│       ├── product.routes.js  # Catalog listing, search, filtering, slug lookup
│       ├── category.routes.js # Category listings & details
│       ├── cart.routes.js     # Cart operations (user & guest sessions)
│       ├── coupon.routes.js   # Coupon validation & discount engine
│       ├── order.routes.js    # Order creation, stock deduction, tracking
│       ├── payment.routes.js  # Razorpay, Stripe & Test Simulator handler
│       ├── admin.routes.js    # Product CRUD, Order status, Sales analytics
│       └── seo.routes.js      # Dynamic robots.txt & sitemap.xml
└── public/
    ├── index.html          # Storefront Home Page
    ├── shop.html           # Full Catalog with Live Filters
    ├── product.html        # Product details & Photo Gallery
    ├── cart.html           # Cart with Free Shipping Progress
    ├── checkout.html       # Checkout & Payment Selector
    ├── order-success.html  # Order Receipt Invoice & Status Timeline
    ├── orders.html         # Customer My Orders Tracker
    ├── login.html          # Secure Login with 1-Click Demo Fill
    ├── register.html       # Customer Sign Up
    ├── favicon.svg         # Modern Brand Favicon
    ├── css/
    │   ├── main.css        # Mobile-first CSS design system
    │   └── admin.css       # Admin dashboard styles & data tables
    ├── js/
    │   ├── api.js          # API client, JWT management, toast notifications
    │   ├── main.js         # Global navbar, live search, cart counter
    │   └── admin.js        # Admin security auth guard & CRUD logic
    └── uploads/            # Uploaded product images
```

---

## 🔒 Security Architecture (Section 15)

- **Role-Based Access Control**: All `/api/admin/*` endpoints strictly verify JWT identity and enforce `role === 'admin'`, returning `403 Forbidden` for unauthorized requests. Admin frontend pages execute an immediate client guard redirecting unauthorized users.
- **Input Sanitization**: HTML tags stripped and parameters sanitized against XSS and injection.
- **SQL Injection Defense**: 100% of SQLite database queries use parameterized prepared statements (`stmt.run(params)` / `stmt.all(params)`).
- **Password Security**: Bcrypt with 10 salt rounds for all user and admin passwords.
- **Rate Limiting**: Brute-force protection on authentication endpoints (30 attempts / 15 mins) and general API endpoints (500 requests / 15 mins).
- **Payment Verification**: Cryptographic HMAC-SHA256 signature verification for payment callbacks and webhooks. No credit card or CVV details are ever stored locally.

---

© 2026 Nexus Store. Completely customizable for your own brand, inventory, and custom domain.
