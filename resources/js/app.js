import './bootstrap';

import Alpine from '@alpinejs/csp';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Global Language Switcher
window.changeLanguage = function(lang) {
    document.querySelectorAll('[data-id]').forEach(function(element) {
        element.innerHTML = lang === 'en' ? element.getAttribute('data-en') : element.getAttribute('data-id');
    });
};

// Global AJAX Pagination & Catalog Logic
window.initPaginationAjax = function() {
    const paginationContainer = document.getElementById('catalog-pagination-container');
    if (!paginationContainer) return;

    paginationContainer.querySelectorAll('.pagination-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            if (!targetUrl || targetUrl === '#' || targetUrl.trim() === '') return;

            window.loadCatalogPage(targetUrl, true);
        });
    });
};

window.selectCategory = function(categorySlug) {
    const url = new URL(window.location.href);
    if (!categorySlug || categorySlug.trim() === '') {
        url.searchParams.delete('category');
    } else {
        url.searchParams.set('category', categorySlug);
    }
    url.searchParams.delete('page');

    const hiddenCatInput = document.getElementById('hidden_category');
    if (hiddenCatInput) {
        hiddenCatInput.value = categorySlug || '';
    }

    window.loadCatalogPage(url.toString(), true);
};

window.loadCatalogPage = function(url, pushState = true) {
    const grid = document.getElementById('tenant-cards-grid');
    const paginationContainer = document.getElementById('catalog-pagination-container');
    const loadingBar = document.getElementById('catalog-loading-bar');
    const anchor = document.getElementById('catalog-section-anchor');

    if (!grid) return;

    if (loadingBar) {
        loadingBar.classList.remove('opacity-0');
        loadingBar.classList.add('opacity-100');
    }

    grid.classList.add('opacity-25', 'scale-[0.98]', 'pointer-events-none');
    if (paginationContainer) {
        paginationContainer.classList.add('opacity-40', 'pointer-events-none');
    }

    if (anchor) {
        const rect = anchor.getBoundingClientRect();
        if (rect.top < 0 || rect.top > window.innerHeight * 0.4) {
            anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal mengambil data halaman.');
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newGrid = doc.getElementById('tenant-cards-grid');
        const newPagination = doc.getElementById('catalog-pagination-container');
        const newFilterBar = doc.getElementById('catalog-filter-bar');
        const newCategoriesSection = doc.getElementById('favorite-categories-section');
        const newHiddenCat = doc.getElementById('hidden_category');

        if (newGrid && grid) {
            grid.innerHTML = newGrid.innerHTML;

            const cards = grid.querySelectorAll('.tenant-card');
            cards.forEach((card, index) => {
                card.classList.remove('animate-catalog-card');
                void card.offsetWidth;
                card.classList.add('animate-catalog-card');
                card.style.animationDelay = `${index * 60}ms`;
            });
        }

        if (newPagination && paginationContainer) {
            paginationContainer.innerHTML = newPagination.innerHTML;
        }

        const currentFilterBar = document.getElementById('catalog-filter-bar');
        if (newFilterBar && currentFilterBar) {
            currentFilterBar.innerHTML = newFilterBar.innerHTML;
        }

        const currentCategoriesSection = document.getElementById('favorite-categories-section');
        if (newCategoriesSection && currentCategoriesSection) {
            currentCategoriesSection.innerHTML = newCategoriesSection.innerHTML;
        }

        const currentHiddenCat = document.getElementById('hidden_category');
        if (newHiddenCat && currentHiddenCat) {
            currentHiddenCat.value = newHiddenCat.value;
        }

        if (pushState) {
            window.history.pushState({ path: url }, '', url);
        }

        const langBtn = document.querySelector('[x-data]');
        const currentLang = (langBtn && langBtn._x_dataStack && langBtn._x_dataStack[0]) 
            ? langBtn._x_dataStack[0].lang 
            : 'id';
        if (typeof window.changeLanguage === 'function') {
            window.changeLanguage(currentLang);
        }

        window.initPaginationAjax();
    })
    .catch(err => {
        console.error('Pindah halaman via ajax gagal, fallback ke navigasi standar:', err);
        window.location.href = url;
    })
    .finally(() => {
        if (loadingBar) {
            loadingBar.classList.remove('opacity-100');
            loadingBar.classList.add('opacity-0');
        }
        grid.classList.remove('opacity-25', 'scale-[0.98]', 'pointer-events-none');
        if (paginationContainer) {
            paginationContainer.classList.remove('opacity-40', 'pointer-events-none');
        }
    });
};

document.addEventListener('DOMContentLoaded', function() {
    window.initPaginationAjax();
});

window.addEventListener('popstate', function() {
    window.loadCatalogPage(window.location.href, false);
});

Alpine.start();
