// Centralized JavaScript for ChapConnect

// 1. Auth Form Toggle (used in login/registration page)
function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    const targetForm = document.getElementById(formId);
    if (targetForm) {
        targetForm.classList.add("active");
        if (window.jQuery && typeof jQuery.fn.select2 === 'function' && jQuery('#categories').length) {
            jQuery('#categories').select2({
                width: '100%',
                minimumResultsForSearch: 0
            });
        }
    }
}

// Category Menu Filter (mobile)
function filterMenu(query) {
    const items = document.querySelectorAll('#menuList li');
    const q = query.trim().toLowerCase();
    items.forEach(li => {
        const name = (li.getAttribute('data-name') || '').toLowerCase();
        li.style.display = (!q || name.includes(q)) ? '' : 'none';
    });
}

// Off-Canvas Left Side Navigation Drawer Controls
function toggleMobileNav() {
    const navMenu = document.getElementById("navIconMenu");
    const backdrop = document.getElementById("drawerBackdrop");
    const toggleBtn = document.getElementById("navToggleBtn");
    if (!navMenu) return;
    
    const isOpen = navMenu.classList.toggle("open");
    if (backdrop) {
        backdrop.classList.toggle("open", isOpen);
    }
    
    // Lock background scroll when drawer is open
    document.body.style.overflow = isOpen ? "hidden" : "";
    
    if (toggleBtn) {
        const icon = toggleBtn.querySelector("i");
        if (icon) {
            icon.className = isOpen ? "bi bi-x-lg" : "bi bi-list";
        }
    }
}

function closeMobileNav() {
    const navMenu = document.getElementById("navIconMenu");
    const backdrop = document.getElementById("drawerBackdrop");
    const toggleBtn = document.getElementById("navToggleBtn");
    
    if (navMenu) navMenu.classList.remove("open");
    if (backdrop) backdrop.classList.remove("open");
    document.body.style.overflow = "";
    
    if (toggleBtn) {
        const icon = toggleBtn.querySelector("i");
        if (icon) icon.className = "bi bi-list";
    }
}

// Close navigation drawer when Escape key is pressed
document.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        closeMobileNav();
    }
});

// Password visibility toggler
function togglePasswordVisibility(button) {
    const wrapper = button.closest('.password-wrapper');
    const input = wrapper ? wrapper.querySelector('input') : null;
    const icon = button.querySelector('i');
    if (!input) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.className = 'bi bi-eye-slash';
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.className = 'bi bi-eye';
        }
    }
}

// 2. Profile Page Tab Switcher
// Handles URL hash changes to activate tabs. e.g. profile.html#photos
function initProfileTabs() {
    const tabLinks = document.querySelectorAll(".profile-sidebar .menu ul li a");
    const sections = document.querySelectorAll(".profile-tab-section");

    if (tabLinks.length === 0) return; // Not a profile page

    function activateTabFromHash() {
        const hash = window.location.hash || "#info-tab"; // Default section id or first tab
        let found = false;

        // Try to match tab href hash to URL hash
        tabLinks.forEach(link => {
            const href = link.getAttribute("href");
            const linkHash = href.substring(href.indexOf("#"));

            if (linkHash === hash) {
                link.classList.add("active");
                found = true;
            } else {
                link.classList.remove("active");
            }
        });

        // Toggle visibility of section containers
        sections.forEach(section => {
            const sectionId = "#" + section.id;
            if (sectionId === hash || (!found && sectionId === "#info-tab")) {
                section.classList.add("active");
            } else {
                section.classList.remove("active");
            }
        });
    }

    // Set up click handlers on tabs to set hash smoothly
    tabLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            const href = this.getAttribute("href");
            if (href.startsWith("#") || href.includes("#")) {
                // If it is an internal hash link, update hash
                const linkHash = href.substring(href.indexOf("#"));
                window.location.hash = linkHash;
                e.preventDefault();
            }
        });
    });

    // Listen to hash change event
    window.addEventListener("hashchange", activateTabFromHash);

    // Initial activation
    activateTabFromHash();
}

// 3. Interactive Profile Card Features (Like/Follow)
function initProfileInteractions() {
    const likeBtn = document.getElementById('likeBtn');
    const likeCountElement = document.getElementById('likeCount');
    const followBtn = document.getElementById('followBtn');
    const followersCountElement = document.getElementById('followersCount');

    if (!likeBtn && !followBtn) return; // Not the home/card page

    const profileId = "zellah_msekeni";

    // Load state from localStorage
    let likes = parseInt(localStorage.getItem(`${profileId}_likes`)) || 24;
    let followers = parseInt(localStorage.getItem(`${profileId}_followers`)) || 148;
    let isLiked = localStorage.getItem(`${profileId}_isLiked`) === "true";
    let isFollowing = localStorage.getItem(`${profileId}_isFollowing`) === "true";

    // Initial render
    if (likeCountElement) likeCountElement.textContent = likes;
    if (followersCountElement) followersCountElement.textContent = followers;

    if (likeBtn) {
        if (isLiked) {
            likeBtn.textContent = 'Liked ❤️';
            likeBtn.classList.add('liked');
        } else {
            likeBtn.textContent = 'Like 🤍';
            likeBtn.classList.remove('liked');
        }

        likeBtn.addEventListener('click', () => {
            if (!isLiked) {
                likes++;
                likeBtn.textContent = 'Liked ❤️';
                likeBtn.classList.add('liked');
                isLiked = true;
            } else {
                likes--;
                likeBtn.textContent = 'Like 🤍';
                likeBtn.classList.remove('liked');
                isLiked = false;
            }
            if (likeCountElement) likeCountElement.textContent = likes;
            localStorage.setItem(`${profileId}_likes`, likes);
            localStorage.setItem(`${profileId}_isLiked`, isLiked);
        });
    }

    if (followBtn) {
        if (isFollowing) {
            followBtn.textContent = 'Following';
            followBtn.classList.add('following');
        } else {
            followBtn.textContent = 'Followers';
            followBtn.classList.remove('following');
        }

        followBtn.addEventListener('click', () => {
            if (!isFollowing) {
                followers++;
                followBtn.textContent = 'Following';
                followBtn.classList.add('following');
                isFollowing = true;
            } else {
                followers--;
                followBtn.textContent = 'Followers';
                followBtn.classList.remove('following');
                isFollowing = false;
            }
            if (followersCountElement) followersCountElement.textContent = followers;
            localStorage.setItem(`${profileId}_followers`, followers);
            localStorage.setItem(`${profileId}_isFollowing`, isFollowing);
        });
    }
}

function initAuthForms() {
    function activateFormFromHash() {
        if (window.location.hash === "#register-form") {
            showForm("register-form");
        } else if (window.location.hash === "#login-form") {
            showForm("login-form");
        }
    }
    window.addEventListener("hashchange", activateFormFromHash);
    activateFormFromHash();
}

function initMobileNavHover() {
    const mobileList = document.querySelector(".nav-mobile-list");
    if (!mobileList) return;
    const mobileLinks = mobileList.querySelectorAll(".nav-mobile-link");

    function clearHover() {
        mobileLinks.forEach(l => l.classList.remove("is-hovered"));
        mobileList.classList.remove("has-hovered");
    }

    function setHover(targetLink) {
        if (!targetLink || !targetLink.classList.contains("nav-mobile-link")) return;
        mobileLinks.forEach(l => l.classList.remove("is-hovered"));
        targetLink.classList.add("is-hovered");
        mobileList.classList.add("has-hovered");
    }

    mobileLinks.forEach(link => {
        // Mouse / Pointer events for desktop & emulator
        link.addEventListener("mouseenter", () => setHover(link));
        link.addEventListener("mouseleave", clearHover);
        link.addEventListener("pointerenter", () => setHover(link));
        link.addEventListener("pointerleave", clearHover);

        // Touch events for real mobile screens
        link.addEventListener("touchstart", () => setHover(link), { passive: true });
    });

    // Touch drag tracking across mobile links
    mobileList.addEventListener("touchmove", (e) => {
        if (e.touches && e.touches[0]) {
            const touch = e.touches[0];
            const elem = document.elementFromPoint(touch.clientX, touch.clientY);
            if (elem) {
                const targetLink = elem.closest(".nav-mobile-link");
                if (targetLink) {
                    setHover(targetLink);
                }
            }
        }
    }, { passive: true });

    mobileList.addEventListener("touchend", () => {
        setTimeout(clearHover, 500);
    });
}

// Auto-run on DOM content loaded
document.addEventListener("DOMContentLoaded", () => {
    initProfileTabs();
    initProfileInteractions();
    initAuthForms();
    initMobileNavHover();
});