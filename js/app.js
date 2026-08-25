"use strict";

const CATALOG = [
  { id: 1, name: "Laptop Aura Pro 14\"", price: 1299, category: "Computación", icon: "laptop" },
  { id: 2, name: "Teclado mecánico Táctil 65%", price: 89, category: "Computación", icon: "keyboard" },
  { id: 3, name: "Mouse inalámbrico Glide MX", price: 45, category: "Computación", icon: "mouse" },
  { id: 4, name: "Audífonos Sono Quiet ANC", price: 199, category: "Audio", icon: "headphones" },
  { id: 5, name: "Parlante Pulse Mini", price: 59, category: "Audio", icon: "speaker" },
  { id: 6, name: "Smartwatch Cronos S", price: 149, category: "Wearables", icon: "watch" },
  { id: 7, name: "Teléfono Nova X5", price: 599, category: "Móviles", icon: "phone" },
  { id: 8, name: "Cámara Visor One 4K", price: 749, category: "Fotografía", icon: "camera" },
];

const CATEGORY_ICONS = {
  laptop: '<path d="M6 5.5h12v10H6z"/><path d="M3.5 18.5h17"/>',
  keyboard: '<rect x="3" y="7" width="18" height="10" rx="1.5"/><path d="M7 11h.01M11 11h.01M15 11h.01M8 14h8"/>',
  mouse: '<rect x="7" y="3.5" width="10" height="17" rx="5"/><path d="M12 7v3"/>',
  headphones: '<path d="M4 13c0-4.4 3.6-8 8-8s8 3.6 8 8"/><rect x="3" y="13" width="4" height="6" rx="1.5"/><rect x="17" y="13" width="4" height="6" rx="1.5"/>',
  speaker: '<rect x="6" y="3.5" width="12" height="17" rx="2"/><circle cx="12" cy="14" r="3"/><circle cx="12" cy="7.5" r="1"/>',
  watch: '<circle cx="12" cy="12" r="4.5"/><path d="M9 4.5h6l.5 3M15 19.5H9l-.5-3"/>',
  phone: '<rect x="7" y="3" width="10" height="18" rx="2.5"/><path d="M11 17.5h2"/>',
  camera: '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="m9 7 1.2-2.5h3.6L15 7"/><circle cx="12" cy="13" r="3.5"/>',
};

const BRANCHES = [
  {
    name: "AZUL Las Mercedes",
    address: "Av. Principal de Las Mercedes, C.C. Azul, Nivel 1, Local 12",
    zones: ["Las Mercedes", "El Rosal", "Los Palos Grandes"],
    whatsapp: "584120000001",
    mapUrl: "https://maps.google.com/?q=Las+Mercedes",
  },
  {
    name: "AZUL La Candelaria",
    address: "Av. Urdaneta, Edificio Azul Center, Planta Baja",
    zones: ["Centro", "Altagracia", "La Pastora"],
    whatsapp: "584120000002",
    mapUrl: "https://maps.google.com/?q=La+Candelaria",
  },
  {
    name: "AZUL Millenium",
    address: "C.C. Millenium Mall, Nivel Feria, Local 25",
    zones: ["Los Dos Caminos", "Petare", "La California"],
    whatsapp: "584120000003",
    mapUrl: "https://maps.google.com/?q=Millenium+Mall",
  },
  {
    name: "AZUL Barquisimeto",
    address: "Av. Venezuela entre Av. Argimiro y Av. Los Leones, C.C. Los Próceres",
    zones: ["Zona Centro", "Zona Este", "Zona Norte"],
    whatsapp: "584120000004",
    mapUrl: "https://maps.google.com/?q=Barquisimeto",
  },
];

const PARTNERS = [
  "Alimentos del Valle",
  "Lácteos San Juan",
  "Café Origen",
  "Dulce Caribe",
  "Agua Serena",
  "Bodegas Unidos",
  "Granasol",
  "Frescampo",
];

const CART_STORAGE_KEY = "azul-cart";
const ADMIN_TRIGGER_CLICKS = 5;
const ADMIN_TRIGGER_WINDOW_MS = 1500;

let cart = new Map();
let activeCategory = null;
let searchQuery = "";

const productGrid = document.getElementById("product-grid");
const catalogCount = document.getElementById("catalog-count");
const categoryChips = document.getElementById("category-chips");
const searchInput = document.getElementById("search-input");
const cartButton = document.getElementById("cart-button");
const cartBadge = document.getElementById("cart-badge");
const cartDrawer = document.getElementById("cart-drawer");
const cartOverlay = document.getElementById("cart-overlay");
const cartCloseButton = document.getElementById("cart-close");
const cartItems = document.getElementById("cart-items");
const cartTotalLabel = document.getElementById("cart-total");
const checkoutButton = document.getElementById("checkout-button");
const adminTrigger = document.getElementById("admin-trigger");
const branchGrid = document.getElementById("branch-grid");
const partnersTrack = document.getElementById("partners-track");

function loadCart() {
  try {
    return new Map(JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) ?? []);
  } catch {
    return new Map();
  }
}

function saveCart() {
  localStorage.setItem(CART_STORAGE_KEY, JSON.stringify([...cart]));
}

function formatPrice(value) {
  return `$${value.toFixed(2)}`;
}

function productById(id) {
  return CATALOG.find((product) => product.id === id);
}

function cartTotalItems() {
  let total = 0;
  for (const quantity of cart.values()) total += quantity;
  return total;
}

function cartTotalPrice() {
  let total = 0;
  for (const [id, quantity] of cart) total += productById(id).price * quantity;
  return total;
}

function renderCartBadge() {
  const total = cartTotalItems();
  cartBadge.textContent = String(total);
  cartBadge.classList.toggle("hidden", total === 0);
  cartBadge.classList.toggle("inline-flex", total > 0);
}

function renderCartItems() {
  if (cart.size === 0) {
    cartItems.innerHTML =
      '<div class="flex h-full flex-col items-center justify-center gap-3 py-16 text-center"><p class="font-display text-lg font-bold text-brand-deep">Tu carrito está vacío</p><p class="text-sm text-ink/50">Agrega productos y aparecerán aquí.</p></div>';
    return;
  }

  cartItems.innerHTML = [...cart]
    .map(([id, quantity]) => {
      const product = productById(id);
      return `
      <article class="mb-3 flex items-center gap-3 rounded-2xl border border-brand-mist p-3">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-brand-tint">
          ${iconSvg(product.icon, "h-7 w-7 text-brand")}
        </div>
        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-semibold">${product.name}</p>
          <p class="text-sm text-ink/50">${formatPrice(product.price)}</p>
        </div>
        <div class="flex items-center gap-1.5">
          <button type="button" data-change-quantity="${id}" data-delta="-1" aria-label="Quitar uno"
                  class="h-7 w-7 rounded-full border border-brand-mist text-sm font-semibold text-ink/60 transition hover:border-brand hover:text-brand">−</button>
          <span class="w-6 text-center text-sm font-semibold">${quantity}</span>
          <button type="button" data-change-quantity="${id}" data-delta="1" aria-label="Agregar uno"
                  class="h-7 w-7 rounded-full border border-brand-mist text-sm font-semibold text-ink/60 transition hover:border-brand hover:text-brand">+</button>
        </div>
        <button type="button" data-remove-from-cart="${id}" aria-label="Eliminar ${product.name}"
                class="rounded-full p-1.5 text-ink/30 transition hover:bg-red-50 hover:text-red-500">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
      </article>`;
    })
    .join("");
}

function refreshCartViews() {
  renderCartBadge();
  renderCartItems();
  cartTotalLabel.textContent = formatPrice(cartTotalPrice());
  checkoutButton.disabled = cart.size === 0;
}

function popCartBadge() {
  cartBadge.animate(
    [{ transform: "scale(1)" }, { transform: "scale(1.45)" }, { transform: "scale(1)" }],
    { duration: 350, easing: "ease-out" }
  );
}

function iconSvg(name, cssClasses) {
  return `<svg class="${cssClasses}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">${CATEGORY_ICONS[name]}</svg>`;
}

function visibleProducts() {
  const query = searchQuery.trim().toLowerCase();
  return CATALOG.filter((product) => {
    const matchesCategory = !activeCategory || product.category === activeCategory;
    const matchesQuery = !query || product.name.toLowerCase().includes(query);
    return matchesCategory && matchesQuery;
  });
}

function renderCategoryChips() {
  const categories = [...new Set(CATALOG.map((product) => product.category))];
  const buttons = [{ label: "Todo", value: null }].concat(
    categories.map((category) => ({ label: category, value: category }))
  );

  categoryChips.innerHTML = buttons
    .map(({ label, value }) => {
      const isActive = activeCategory === value;
      const state = isActive
        ? "bg-brand text-paper shadow-md shadow-brand/25"
        : "bg-paper text-ink/70 border border-brand-mist hover:border-brand hover:text-brand";
      return `<button type="button" data-category="${value ?? ""}" class="rounded-full px-4 py-2 text-sm font-medium transition ${state}">${label}</button>`;
    })
    .join("");
}

function renderProductGrid() {
  const products = visibleProducts();
  catalogCount.textContent =
    products.length === 1 ? "1 producto" : `${products.length} productos`;

  if (products.length === 0) {
    productGrid.innerHTML =
      '<p class="col-span-full py-16 text-center text-ink/50">No encontramos ese producto. Prueba con otra búsqueda.</p>';
    return;
  }

  productGrid.innerHTML = products
    .map(
      (product) => `
      <article class="group flex flex-col overflow-hidden rounded-3xl bg-paper ring-1 ring-brand-mist transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10">
        <div class="relative flex h-44 items-center justify-center bg-gradient-to-br from-brand-tint to-brand-mist">
          ${iconSvg(product.icon, "h-16 w-16 text-brand transition duration-300 group-hover:scale-110")}
          <span class="absolute top-3 left-3 rounded-full bg-paper/80 px-2.5 py-1 text-[11px] font-semibold text-brand-dark">${product.category}</span>
        </div>
        <div class="flex flex-1 flex-col p-5">
          <h3 class="font-semibold text-ink">${product.name}</h3>
          <p class="mt-1 font-display text-xl font-bold text-brand-deep">${formatPrice(product.price)}</p>
          <button type="button" data-add-to-cart="${product.id}"
                  class="mt-4 w-full rounded-full bg-brand-deep py-2.5 text-sm font-semibold text-paper transition hover:bg-brand">
            Agregar al carrito
          </button>
        </div>
      </article>`
    )
    .join("");
}

function renderBranches() {
  branchGrid.innerHTML = BRANCHES.map(
    (branch) => `
    <article class="flex flex-col rounded-3xl bg-paper p-6 shadow-sm ring-1 ring-brand-mist transition hover:shadow-lg hover:shadow-brand/10">
      <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-tint">
        <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-5.1-7-11a7 7 0 1 1 14 0c0 5.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
      </div>
      <h3 class="font-display text-lg font-bold text-brand-deep">${branch.name}</h3>
      <p class="mt-1.5 text-sm leading-relaxed text-ink/50">${branch.address}</p>
      <div class="mt-4">
        <p class="text-xs font-semibold tracking-wide text-ink/40 uppercase">Delivery disponible en</p>
        <div class="mt-2 flex flex-wrap gap-1.5">
          ${branch.zones.map((zone) => `<span class="rounded-full bg-brand-tint px-2.5 py-1 text-xs font-medium text-brand-dark">${zone}</span>`).join("")}
        </div>
      </div>
      <div class="mt-auto flex gap-2 pt-5">
        <a href="https://wa.me/${branch.whatsapp}" target="_blank" rel="noopener"
           class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-brand py-2.5 text-xs font-semibold text-paper transition hover:bg-brand-dark">
          <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.6A9 9 0 1 1 21 12z"/></svg>
          Pedir delivery
        </a>
        <a href="${branch.mapUrl}" target="_blank" rel="noopener"
           class="flex items-center justify-center gap-1.5 rounded-full border border-brand-mist px-4 py-2.5 text-xs font-semibold text-brand-deep transition hover:border-brand hover:text-brand">
          Ubícanos
        </a>
      </div>
    </article>`
  ).join("");
}

function renderPartners() {
  partnersTrack.innerHTML = PARTNERS.concat(PARTNERS)
    .map((partner) => `<span class="shrink-0 font-display text-xl font-bold whitespace-nowrap text-ink/25 transition hover:text-brand/50">${partner}</span>`)
    .join("");
}

function addToCart(productId) {
  cart.set(productId, (cart.get(productId) ?? 0) + 1);
  saveCart();
  refreshCartViews();
  popCartBadge();
}

function removeFromCart(productId) {
  cart.delete(productId);
  saveCart();
  refreshCartViews();
}

function changeQuantity(productId, delta) {
  const nextQuantity = (cart.get(productId) ?? 0) + delta;
  if (nextQuantity <= 0) return removeFromCart(productId);
  cart.set(productId, nextQuantity);
  saveCart();
  refreshCartViews();
}

function navigateToAdmin() {
  window.location.href = "admin.html";
}

function bindEvents() {
  categoryChips.addEventListener("click", (event) => {
    const button = event.target.closest("[data-category]");
    if (!button) return;
    activeCategory = button.dataset.category || null;
    renderCategoryChips();
    renderProductGrid();
  });

  searchInput.addEventListener("input", () => {
    searchQuery = searchInput.value;
    renderProductGrid();
  });

  productGrid.addEventListener("click", (event) => {
    const button = event.target.closest("[data-add-to-cart]");
    if (button) addToCart(Number(button.dataset.addToCart));
  });

  cartItems.addEventListener("click", (event) => {
    const addOne = event.target.closest("[data-change-quantity]");
    if (addOne) {
      changeQuantity(Number(addOne.dataset.changeQuantity), Number(addOne.dataset.delta));
      return;
    }
    const remove = event.target.closest("[data-remove-from-cart]");
    if (remove) removeFromCart(Number(remove.dataset.removeFromCart));
  });

  cartButton.addEventListener("click", openCart);
  cartCloseButton.addEventListener("click", closeCart);
  cartOverlay.addEventListener("click", closeCart);

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closeCart();
    if (event.ctrlKey && event.shiftKey && event.key.toLowerCase() === "a") navigateToAdmin();
  });

  checkoutButton.addEventListener("click", () => {
    alert("Checkout demo: aquí se integrará el backend de Laravel.");
  });

  let adminClickTimestamps = [];
  adminTrigger.addEventListener("click", () => {
    const now = Date.now();
    adminClickTimestamps = adminClickTimestamps.filter((time) => now - time < ADMIN_TRIGGER_WINDOW_MS);
    adminClickTimestamps.push(now);
    if (adminClickTimestamps.length >= ADMIN_TRIGGER_CLICKS) navigateToAdmin();
  });
}

function openCart() {
  cartDrawer.classList.remove("translate-x-full");
  cartOverlay.classList.remove("hidden");
  document.body.style.overflow = "hidden";
}

function closeCart() {
  cartDrawer.classList.add("translate-x-full");
  cartOverlay.classList.add("hidden");
  document.body.style.overflow = "";
}

cart = loadCart();
renderCategoryChips();
renderProductGrid();
renderBranches();
renderPartners();
refreshCartViews();
bindEvents();
