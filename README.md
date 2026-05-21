# Traveloop — Tourism Catalog Website

A modern, responsive tourism catalog website built with **Bootstrap 5** and **Google Places API**.

## 📁 File Structure

```
tourism/
├── index.html          # Homepage (Hero, Categories, Featured)
├── explore.html        # Explore page (Grid, Filter, Pagination)
├── map.html            # Interactive Google Maps page
├── style.css           # Main design system & global styles
├── script.js           # All frontend logic
├── config.js           # API config + Mock data + localStorage DB
└── admin/
    ├── index.html      # Admin panel (Dashboard, CRUD, Settings)
    ├── admin.css       # Admin-specific styles
    └── admin.js        # Admin panel logic
```

## 🚀 Setup

### Option A: Without Google Maps API Key (Mock Data)
Just open `index.html` in a browser or with VS Code Live Server.
The website uses 12 built-in Indonesian destinations as demo data.

### Option B: With Google Maps API Key (Real Data)

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Enable these APIs:
   - **Maps JavaScript API**
   - **Places API**
   - **Maps Embed API**
3. Create an API key
4. Replace `YOUR_GOOGLE_MAPS_API_KEY` in:
   - `config.js` (line 7)
   - `map.html` (last `<script>` tag)
5. Or set it from the **Admin Panel → Settings tab**

## 🔑 Admin Panel

- URL: `/admin/admin-index.html`
- Default password: `admin123`
- Features: Dashboard stats, CRUD for places, user management, settings, JSON export

## ✨ Features

| Feature | Status |
|---|---|
| Responsive Design (mobile/tablet/desktop) | ✅ |
| Hero with animated particles | ✅ |
| Search by city/keyword | ✅ |
| Filter by category, rating, distance | ✅ |
| Grid & List view toggle | ✅ |
| Pagination | ✅ |
| Detail modal with map embed | ✅ |
| Favourites (localStorage) | ✅ |
| Interactive Google Maps page | ✅ |
| Admin Dashboard with stats | ✅ |
| Admin CRUD (Add/Edit/Delete places) | ✅ |
| Admin Settings (API key, export) | ✅ |
| localStorage database | ✅ |

## 🎨 Design

- **Font**: Playfair Display (headings) + DM Sans (body)
- **Palette**: Deep navy (#0f172a) + Amber gold (#eab308)
- **Style**: Editorial travel magazine aesthetic
- **Animations**: Smooth hover, card elevation, particle hero

## 🛠 Tech Stack

- HTML5 / CSS3 / Vanilla JavaScript
- Bootstrap 5.3
- Bootstrap Icons
- Google Places API (optional)
- Google Maps JavaScript API (optional)
- localStorage (client-side DB)
