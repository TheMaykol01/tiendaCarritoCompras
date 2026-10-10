"use strict";

/**
 * Los datos del catálogo y las sucursales los aporta Laravel en el DOM
 * (elementos [data-product] y .branch-card). Este script solo se encarga
 * del carrito, los filtros y la búsqueda.
 */

const CART_STORAGE_KEY = "azul-cart";

const cart = new Map();

let activeCategory = null;

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

/** Catálogo leído del DOM, indexado por id. */
const catalog = new Map();

function hydrateCatalog() {
  for (const card of document.querySelectorAll("[data-product-id]")) {
    catalog.set(Number(card.dataset.productId), {
      id: Number(card.dataset.productId),
      name: card.dataset.productName,
      price: Number(card.dataset.productPrice),
      category: card.dataset.productCategory,
    });
  }
}

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
  return catalog.get(id);
}

function cartTotalItems() {
  let total = 0;
  for (const quantity of cart.values()) total += quantity;
  return total;
}

function cartTotalPrice() {
  let total = 0;
  for (const [id, quantity] of cart) {
    const product = productById(id);
    if (product) total += product.price * quantity;
  }
  return total;
}

function renderCartBadge() {
  const total = cartTotalItems();
  cartBadge.textContent = String(total);
  cartBadge.classList.toggle("is-visible", total > 0);
}

function renderCartItems() {
  if (cart.size === 0) {
    cartItems.innerHTML = `
      <div class="cart-empty">
        <p class="cart-empty__title">Tu carrito está vacío</p>
        <p class="text-muted">Agrega productos y aparecerán aquí.</p>
      </div>`;
    return;
  }

  cartItems.innerHTML = [...cart]
    .filter(([id]) => productById(id))
    .map(([id, quantity]) => {
      const product = productById(id);
      return `
      <article class="cart-item">
        <div class="cart-item__thumb">
          <svg class="icon--md" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="7" width="14" height="12" rx="2"/><path d="M9 7V5h6v2"/></svg>
        </div>
        <div class="cart-item__info">
          <p class="cart-item__name">${product.name}</p>
          <p class="cart-item__price">${formatPrice(product.price)}</p>
        </div>
        <div class="cart-item__controls">
          <button type="button" data-change-quantity="${id}" data-delta="-1" aria-label="Quitar uno"
                  class="cart-item__step">−</button>
          <span class="cart-item__quantity">${quantity}</span>
          <button type="button" data-change-quantity="${id}" data-delta="1" aria-label="Agregar uno"
                  class="cart-item__step">+</button>
        </div>
        <button type="button" data-remove-from-cart="${id}" aria-label="Eliminar ${product.name}"
                class="cart-item__remove">
          <svg class="icon--sm" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
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

function updateCatalogCount() {
  if (!productGrid || !catalogCount) return;

  const visible = [...productGrid.querySelectorAll("[data-product-id]")].filter((c) => !c.hidden);
  catalogCount.textContent = visible.length === 1 ? "1 producto" : `${visible.length} productos`;
}

function applyFilters() {
  if (!productGrid) return;

  const query = searchInput ? searchInput.value.trim().toLowerCase() : "";

  for (const card of productGrid.querySelectorAll("[data-product-id]")) {
    const matchesCategory =
      !activeCategory || card.dataset.productCategory === activeCategory;
    const matchesQuery =
      !query || card.dataset.productName.toLowerCase().includes(query);
    card.hidden = !(matchesCategory && matchesQuery);
  }

  updateCatalogCount();
}

function markActiveChip(value) {
  if (!categoryChips) return;

  for (const chip of categoryChips.querySelectorAll(".chip")) {
    chip.classList.toggle("chip--active", chip.dataset.category === value);
  }
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

function openCart() {
  cartDrawer.classList.add("is-open");
  cartOverlay.classList.add("is-open");
  document.body.style.overflow = "hidden";
}

function closeCart() {
  cartDrawer.classList.remove("is-open");
  cartOverlay.classList.remove("is-open");
  document.body.style.overflow = "";
}

function bindEvents() {
  if (categoryChips) {
    categoryChips.addEventListener("click", (event) => {
      const chip = event.target.closest("[data-category]");
      if (!chip) return;
      activeCategory = chip.dataset.category || null;
      markActiveChip(chip.dataset.category);
      applyFilters();
    });
  }

  if (searchInput) {
    searchInput.addEventListener("input", () => {
      activeCategory = null;
      markActiveChip("");
      applyFilters();
    });
  }

  if (productGrid) {
    productGrid.addEventListener("click", (event) => {
      const button = event.target.closest("[data-add-to-cart]");
      if (button) addToCart(Number(button.dataset.addToCart));
    });
  }

  if (!cartButton) return;

  cartItems.addEventListener("click", (event) => {
    const step = event.target.closest("[data-change-quantity]");
    if (step) {
      changeQuantity(Number(step.dataset.changeQuantity), Number(step.dataset.delta));
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
  });

  checkoutButton.addEventListener("click", () => {
    alert("Checkout demo: aquí se integrará el backend de Laravel.");
  });
}

hydrateCatalog();
markActiveChip("");
updateCatalogCount();

if (cartButton) {
  for (const [id, quantity] of loadCart()) cart.set(id, quantity);

  refreshCartViews();
  bindEvents();
}
