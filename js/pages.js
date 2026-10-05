// Page logic for Phase 1: login, add listing, my listings, marketplace, admin approvals.
document.addEventListener('DOMContentLoaded', () => {
    const { api, esc, imgUrl, BASE } = BH;
    let refreshPurchases = () => {};
    const dashboardListings = document.getElementById('dashboard-recent-listings');
    if (dashboardListings) {
        const loadDashboard = async () => {
            const activeCount = document.getElementById('dashboard-active-listings');
            const wishlistCount = document.getElementById('dashboard-wishlist-count');
            const purchasesCount = document.getElementById('dashboard-purchases-count');
            const soldCount = document.getElementById('dashboard-sold-count');

            try {
                const [listingResult, wishlistResult, purchaseResult] = await Promise.all([
                    api('listings/mine.php'),
                    api('wishlist/list.php'),
                    api('purchases/mine.php')
                ]);
                const errors = [];

                if (listingResult.success) {
                    const listings = listingResult.listings;
                    activeCount.textContent = listings.filter(listing => listing.status === 'approved').length;
                    soldCount.textContent = listings.filter(listing => listing.status === 'sold').length;
                    dashboardListings.innerHTML = listings.length
                        ? listings.slice(0, 3).map(listing => {
                            const statusLabels = {
                                pending: 'Pending',
                                approved: 'Available',
                                rejected: 'Rejected',
                                sold: 'Sold'
                            };
                            const statusClasses = {
                                pending: 'pending',
                                approved: 'available',
                                rejected: 'rejected',
                                sold: 'sold'
                            };
                            return `<tr>
                              <td><div class="book-cell"><img class="book-thumb" src="${esc(imgUrl(listing.image_url))}" alt=""><div><strong>${esc(listing.title)}</strong><span>${esc(listing.course_code)}</span></div></div></td>
                              <td>৳${Number(listing.price).toLocaleString()}</td>
                              <td><span class="status ${statusClasses[listing.status] || ''}">${esc(statusLabels[listing.status] || listing.status)}</span></td>
                              <td><a href="my-listings.php" class="btn btn-outline btn-sm">View</a></td>
                            </tr>`;
                        }).join('')
                        : '<tr><td colspan="4" style="text-align:center;padding:24px;">You have not listed any items yet.</td></tr>';
                } else {
                    activeCount.textContent = '—';
                    soldCount.textContent = '—';
                    dashboardListings.innerHTML = `<tr><td colspan="4">${esc(listingResult.message || 'Could not load your listings.')}</td></tr>`;
                    errors.push(listingResult.message || 'Could not load your listings.');
                }

                if (wishlistResult.success) {
                    wishlistCount.textContent = wishlistResult.listings.length;
                } else {
                    wishlistCount.textContent = '—';
                    errors.push(wishlistResult.message || 'Could not load your wishlist count.');
                }

                if (purchaseResult.success) {
                    purchasesCount.textContent = purchaseResult.requests.length;
                } else {
                    purchasesCount.textContent = '—';
                    errors.push(purchaseResult.message || 'Could not load your purchase requests.');
                }

                if (errors.length) showToast(errors.join(' '));
            } catch {
                activeCount.textContent = '—';
                wishlistCount.textContent = '—';
                purchasesCount.textContent = '—';
                soldCount.textContent = '—';
                dashboardListings.innerHTML = '<tr><td colspan="4">Could not connect to the server. Please try again.</td></tr>';
                showToast('Could not load dashboard data. Check your connection and try again.');
            }
        };
        BH.ready.then(user => user && user.role === 'student' && loadDashboard());
    }

    const setWishButton = (button, saved) => {
        button.classList.toggle('active', saved);
        button.setAttribute('aria-pressed', String(saved));
        button.setAttribute('aria-label', saved ? 'Remove from wishlist' : 'Add to wishlist');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-solid', saved);
            icon.classList.toggle('fa-regular', !saved);
        }
    };
    const syncStudentActions = async (root = document) => {
        const user = await BH.ready;
        if (!user || user.role !== 'student') return;
        try {
            const [wishlist, purchases] = await Promise.all([
                api('wishlist/list.php'),
                api('purchases/mine.php')
            ]);
            if (wishlist.success) {
                const savedIds = new Set(wishlist.ids.map(String));
                root.querySelectorAll('[data-wishlist-id]').forEach(button => {
                    setWishButton(button, savedIds.has(String(button.dataset.wishlistId)));
                });
            }
            if (purchases.success) {
                const activeRequests = new Set(purchases.requests
                    .filter(request => ['pending', 'accepted'].includes(request.status))
                    .map(request => String(request.listing_id)));
                root.querySelectorAll('[data-buy-request]').forEach(button => {
                    const active = activeRequests.has(String(button.dataset.buyRequest));
                    if (active) {
                        button.disabled = true;
                        button.innerHTML = '<i class="fa-solid fa-check"></i> Request Sent';
                    }
                });
            }
        } catch {
            showToast('Could not sync wishlist and request status.');
        }
    };

    document.addEventListener('click', async e => {
        const wishButton = e.target.closest('[data-wishlist-id]');
        if (wishButton) {
            e.preventDefault();
            const user = await BH.ready;
            if (!user || user.role !== 'student') {
                location.href = BASE + 'login.php';
                return;
            }
            wishButton.disabled = true;
            let result;
            try {
                result = await api('wishlist/toggle.php', { listing_id: wishButton.dataset.wishlistId });
            } catch {
                wishButton.disabled = false;
                showToast('Could not connect. Try again.');
                return;
            }
            wishButton.disabled = false;
            showToast(result.message || 'Could not update wishlist.');
            if (!result.success) return;
            setWishButton(wishButton, result.saved);
            if (!result.saved && wishButton.closest('#wishlist-grid')) {
                wishButton.closest('.product-card').remove();
                const wishlistGrid = document.getElementById('wishlist-grid');
                if (!wishlistGrid.querySelector('.product-card')) wishlistGrid.innerHTML = '<p>Your wishlist is empty.</p>';
            }
            return;
        }

        const buyButton = e.target.closest('[data-buy-request]');
        if (buyButton) {
            e.preventDefault();
            const user = await BH.ready;
            if (!user || user.role !== 'student') {
                location.href = BASE + 'login.php';
                return;
            }
            buyButton.disabled = true;
            let result;
            try {
                result = await api('purchases/request.php', { listing_id: buyButton.dataset.buyRequest });
            } catch {
                buyButton.disabled = false;
                showToast('Could not connect. Try again.');
                return;
            }
            showToast(result.message || 'Could not send buy request.');
            if (result.success) {
                buyButton.innerHTML = '<i class="fa-solid fa-check"></i> Request Sent';
            } else {
                buyButton.disabled = false;
            }
            return;
        }

        const requestAction = e.target.closest('[data-request-action]');
        if (requestAction) {
            const action = requestAction.dataset.requestAction;
            if (action === 'accept' && !confirm('Accept this request and mark the listing as sold?')) return;
            requestAction.disabled = true;
            let result;
            try {
                result = await api('purchases/respond.php', {
                    request_id: requestAction.dataset.requestId,
                    action
                });
            } catch {
                requestAction.disabled = false;
                showToast('Could not connect. Try again.');
                return;
            }
            showToast(result.message || 'Could not update buy request.');
            if (result.success) loadIncomingRequests();
            else requestAction.disabled = false;
            return;
        }

        const reviewButton = e.target.closest('[data-review-request]');
        if (reviewButton) {
            const reviewForm = document.getElementById('review-form');
            reviewForm.reset();
            document.getElementById('review-request-id').value = reviewButton.dataset.reviewRequest;
            document.getElementById('review-item-title').textContent = `Review your purchase of ${reviewButton.dataset.reviewTitle}.`;
            document.getElementById('review-modal').hidden = false;
            document.getElementById('review-rating').focus();
        }
    });

    const reviewCancel = document.getElementById('review-cancel');
    if (reviewCancel) reviewCancel.addEventListener('click', () => { document.getElementById('review-modal').hidden = true; });
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', async e => {
            e.preventDefault();
            const button = reviewForm.querySelector('[type=submit]');
            button.disabled = true;
            try {
                const result = await api('reviews/create.php', new FormData(reviewForm));
                showToast(result.message || 'Could not submit review.');
                if (result.success) {
                    document.getElementById('review-modal').hidden = true;
                    refreshPurchases();
                }
            } catch {
                showToast('Could not connect. Try again.');
            } finally {
                button.disabled = false;
            }
        });
    }

    async function loadSellerReviews(listingId) {
        const box = document.getElementById('seller-reviews');
        if (!box) return;
        try {
            const result = await api(`reviews/list.php?listing_id=${encodeURIComponent(listingId)}`);
            if (!result.success) {
                box.innerHTML = `<p>${esc(result.message || 'Could not load seller reviews.')}</p>`;
                return;
            }
            box.innerHTML = `
              <div class="section-header"><div><h2>Seller Reviews</h2><p>${result.count ? `★ ${Number(result.average).toFixed(1)} average from ${result.count} review${result.count === 1 ? '' : 's'}` : 'No reviews yet.'}</p></div></div>
              ${result.reviews.length ? `<div class="grid grid-2">${result.reviews.map(review => `
                <article class="panel" style="padding:18px;">
                  <div class="rating">★ ${Number(review.rating)}/5 <span style="color:#777;font-weight:500;">by ${esc(review.reviewer_name)}</span></div>
                  ${review.comment ? `<p style="margin-top:10px;">${esc(review.comment)}</p>` : ''}
                  <small style="color:#777;">${new Date(review.created_at).toLocaleDateString()}</small>
                </article>`).join('')}</div>` : '<p style="color:#777;">Seller reviews will appear here after completed purchases.</p>'}`;
        } catch {
            box.innerHTML = '<p>Could not connect while loading seller reviews.</p>';
        }
    }

    /* ---------- LOGIN ---------- */
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        BH.ready.then(u => { if (u) location.href = BASE + (u.role === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'); });
        const modeButtons = loginForm.querySelectorAll('[data-login-mode]');
        const idLabel = document.getElementById('login-id-label');
        const idInput = document.getElementById('login-id');
        const nameGroup = document.getElementById('login-name-group');
        const passwordGroup = document.getElementById('admin-pass-group');
        const passwordInput = document.getElementById('admin-password');
        const loginHelp = document.getElementById('login-help');
        const submitButton = document.getElementById('login-submit');
        let loginMode = 'student';

        const setLoginMode = mode => {
            loginMode = mode;
            modeButtons.forEach(button => {
                const active = button.dataset.loginMode === mode;
                button.classList.toggle('active', active);
                button.setAttribute('aria-pressed', String(active));
            });
            const isAdmin = mode === 'admin';
            idLabel.textContent = isAdmin ? 'Admin ID' : 'Student ID';
            idInput.placeholder = isAdmin ? 'e.g. ADMIN' : 'e.g. 011231234';
            nameGroup.hidden = isAdmin;
            passwordGroup.hidden = !isAdmin;
            passwordInput.required = isAdmin;
            loginHelp.textContent = isAdmin
                ? 'Use the admin ID and password provided by your BookHUB administrator.'
                : 'Students can sign in with their Student ID. New accounts are created automatically.';
        };

        modeButtons.forEach(button => button.addEventListener('click', () => setLoginMode(button.dataset.loginMode)));
        loginForm.addEventListener('submit', async e => {
            e.preventDefault();
            submitButton.disabled = true;
            submitButton.textContent = 'Signing in...';
            try {
                const r = await api('auth/login.php', new FormData(loginForm));
                if (r.success) return void (location.href = BASE + r.redirect);
                if (r.needs_password) {
                    setLoginMode('admin');
                    passwordInput.focus();
                }
                showToast(r.message || 'Login failed.');
            } catch {
                showToast('Could not connect. Check your connection and try again.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Continue';
            }
        });
    }

    /* ---------- ADD LISTING ---------- */
    const listingForm = document.getElementById('listing-form');
    if (listingForm) {
        const fileInput = document.getElementById('photo-input');
        const label = document.getElementById('photo-label');
        document.getElementById('photo-btn').addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => { label.textContent = fileInput.files[0] ? fileInput.files[0].name : 'Drag & drop photos here or choose files'; });
        listingForm.addEventListener('submit', async e => {
            e.preventDefault();
            const btn = listingForm.querySelector('[type=submit]'); btn.disabled = true;
            const r = await api('listings/create.php', new FormData(listingForm));
            btn.disabled = false;
            showToast(r.message || 'Something went wrong.');
            if (r.success) setTimeout(() => location.href = 'my-listings.php', 1200);
        });
    }

    /* ---------- PUBLIC BOOK DONATION ---------- */
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        const fileInput = document.getElementById('donation-photo-input');
        const photoLabel = document.getElementById('donation-photo-label');
        const photoButton = document.getElementById('donation-photo-btn');
        const result = document.getElementById('donation-result');
        photoButton.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => {
            photoLabel.textContent = fileInput.files[0]?.name || 'Choose a photo (JPG/PNG/WEBP, max 2 MB)';
        });
        donationForm.addEventListener('submit', async event => {
            event.preventDefault();
            const button = donationForm.querySelector('[type=submit]');
            button.disabled = true;
            button.textContent = 'Submitting...';
            result.textContent = '';
            try {
                const response = await api('donations/create.php', new FormData(donationForm));
                result.textContent = response.message || 'Could not submit the donation.';
                if (response.success) {
                    donationForm.reset();
                    photoLabel.textContent = 'Choose a photo (JPG/PNG/WEBP, max 2 MB)';
                    result.className = 'notice';
                }
            } catch {
                result.textContent = 'Could not connect. Please try again.';
            } finally {
                button.disabled = false;
                button.textContent = 'Submit Donation';
            }
        });
    }

    /* ---------- MY LISTINGS ---------- */
    const myBody = document.getElementById('my-listings-body');
    if (myBody) {
        const cls = { pending: 'pending', approved: 'available', rejected: 'rejected', sold: 'sold' };
        const lbl = { pending: 'Pending', approved: 'Available', rejected: 'Rejected', sold: 'Sold' };
        const load = async () => {
            try {
                const r = await api('listings/mine.php');
                if (!r.success) {
                    myBody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:24px;">${esc(r.message || 'Could not load your listings.')}</td></tr>`;
                    return;
                }
                myBody.innerHTML = r.listings.length ? r.listings.map(l => `
                  <tr><td><div class="book-cell"><img class="book-thumb" src="${esc(imgUrl(l.image_url))}"><div><strong>${esc(l.title)}</strong><span>${esc(l.item_type)}${l.status === 'rejected' && l.reject_reason ? ' • Reason: ' + esc(l.reject_reason) : ''}</span></div></div></td>
                  <td>${esc(l.course_code)}</td><td>৳${Number(l.price).toLocaleString()}</td>
                  <td><span class="status ${cls[l.status] || ''}">${esc(lbl[l.status] || l.status)}</span></td>
                  <td><div class="table-actions">${l.status === 'approved' ? `<button class="btn btn-danger btn-sm" data-sold="${esc(l.id)}">Sold</button>` : ''}</div></td></tr>`).join('')
                  : '<tr><td colspan="5" style="text-align:center;padding:24px;">No listings yet. Click "Add Listing" to sell your first book.</td></tr>';
            } catch {
                myBody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:24px;">Could not connect to the server. Please refresh and try again.</td></tr>';
            }
        };
        myBody.addEventListener('click', async e => {
            const id = e.target.dataset.sold; if (!id) return;
            if (!confirm('Mark this item as sold?')) return;
            const r = await api('listings/mark_sold.php', { id }); showToast(r.message); load();
        });
        BH.ready.then(u => u && load());
    }

        /* ---------- LISTING DETAILS (public + student) ---------- */
        const detailBox = document.getElementById('product-details') || document.getElementById('student-product-details');
        if (detailBox) {
            const isStudentPage = Boolean(document.getElementById('student-product-details'));
            const id = new URLSearchParams(location.search).get('id');
            const breadcrumb = document.getElementById('detail-breadcrumb') || document.getElementById('student-detail-breadcrumb');
            const moreGrid = document.getElementById('more-listings') || document.getElementById('student-more-listings');
            const detailsBase = isStudentPage ? '' : BASE;
            const loginUrl = `${BASE}login.php`;

            const renderCard = item => `
              <article class="product-card">
                <div class="product-image">
                  <img src="${esc(imgUrl(item.image_url))}" alt="${esc(item.title)}">
                  <span class="status available">${esc(item.condition_status)}</span>
                  <button class="wish" type="button" data-wishlist-id="${esc(item.id)}" aria-label="Add to wishlist" aria-pressed="false"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="product-body">
                  <div class="product-meta"><span class="course">${esc(item.course_code)}</span><span class="dept">${esc(item.department)}</span></div>
                  <h3 class="product-title"><a href="${detailsBase}product-details.php?id=${encodeURIComponent(item.id)}">${esc(item.title)}</a></h3>
                  <div class="seller-line"><span class="rating">${Number(item.seller_rating_count) ? `★ ${Number(item.seller_rating).toFixed(1)}` : 'No ratings'}</span><span>by ${esc(item.seller_name)}</span></div>
                  <div class="product-bottom"><span class="price">৳${Number(item.price).toLocaleString()}</span><a href="${detailsBase}product-details.php?id=${encodeURIComponent(item.id)}" class="btn btn-outline btn-sm">View Details</a></div>
                </div>
              </article>`;

            const load = async () => {
                if (!id) {
                    detailBox.innerHTML = '<p>Listing not found. <a href="marketplace.php">Browse the marketplace</a> and select an item to view its details.</p>';
                    return;
                }
                try {
                    const r = await api('listings/show.php?id=' + encodeURIComponent(id));
                    if (!r.success) {
                        detailBox.innerHTML = `<p>${esc(r.message || 'Could not load this listing.')}</p>`;
                        return;
                    }

                    const listing = r.listing;
                    document.title = `${listing.title} | UIU BookHUB`;
                    if (breadcrumb) breadcrumb.textContent = listing.title;
                    const user = await BH.ready;
                    const canAct = user && user.role === 'student';
                    const isSeller = canAct && Number(user.id) === Number(listing.seller_id);
                    const sellerAction = canAct && !isSeller
                        ? `<a href="${isStudentPage ? '' : BASE + 'student/'}messages.php?listing_id=${encodeURIComponent(listing.id)}" class="btn btn-outline btn-sm">Chat with Seller</a>`
                        : isSeller
                            ? '<span>You are the seller</span>'
                        : `<a href="${loginUrl}" class="btn btn-outline btn-sm">Login to Chat</a>`;
                    const actionNote = isSeller
                        ? 'This is your listing.'
                        : canAct
                        ? 'Open Messages to discuss this listing with the seller.'
                        : 'Seller contact details are hidden from guests. Login to chat or request this item.';
                    const isSold = listing.listing_status === 'sold';
                    const requestAction = canAct && !isSeller && !isSold
                        ? `<button type="button" class="btn btn-primary btn-large" data-buy-request="${esc(listing.id)}"><i class="fa-solid fa-cart-shopping"></i> Request to Buy</button>`
                        : isSold
                            ? '<button type="button" class="btn btn-primary btn-large" disabled>Sold</button>'
                            : isSeller
                                ? ''
                                : `<a href="${loginUrl}" class="btn btn-primary btn-large"><i class="fa-solid fa-cart-shopping"></i> Request to Buy</a>`;
                    const wishlistAction = canAct && !isSeller && !isSold
                        ? `<button type="button" class="btn btn-outline btn-large" data-wishlist-id="${esc(listing.id)}" aria-label="Add to wishlist" aria-pressed="false"><i class="fa-regular fa-heart"></i> Wishlist</button>`
                        : isSold
                            ? ''
                            : isSeller
                                ? ''
                                : `<a href="${loginUrl}" class="btn btn-outline btn-large"><i class="fa-regular fa-heart"></i> Wishlist</a>`;

                    detailBox.innerHTML = `
                      <div class="detail-grid">
                        <div><div class="gallery-main"><img src="${esc(imgUrl(listing.image_url))}" alt="${esc(listing.title)}"></div></div>
                        <div class="detail-info">
                          <span class="status ${isSold ? 'sold' : 'available'}">${isSold ? 'Sold' : 'Available'}</span>
                          <h1>${esc(listing.title)}</h1>
                          <p class="detail-course">${esc(listing.course_code)} · ${esc(listing.department)}${listing.subject ? ' · ' + esc(listing.subject) : ''}</p>
                          <div class="detail-price">৳${Number(listing.price).toLocaleString()}</div>
                          <div class="rating">${Number(listing.seller_rating_count) ? `★ ${Number(listing.seller_rating).toFixed(1)} <span style="color:#777;font-weight:500;">seller rating (${Number(listing.seller_rating_count)} ${Number(listing.seller_rating_count) === 1 ? 'review' : 'reviews'})</span>` : 'No seller reviews yet'}</div>
                          <div class="detail-actions">
                            ${requestAction}
                            ${wishlistAction}
                          </div>
                          <div class="info-list">
                            <div class="info-row"><span>Condition</span><span>${esc(listing.condition_status)}</span></div>
                            <div class="info-row"><span>Edition</span><span>${esc(listing.edition_author || 'Not specified')}</span></div>
                            <div class="info-row"><span>Type</span><span>${esc(listing.item_type)}</span></div>
                            <div class="info-row"><span>Availability</span><span style="color:${isSold ? '#777' : '#16834B'};">${isSold ? 'Sold' : 'Available'}</span></div>
                          </div>
                          <div class="seller-box">
                            <div class="avatar">${esc(BH.initials(listing.seller_name))}</div><div style="flex:1"><h4>${esc(listing.seller_name)}</h4><p>UIU Student${Number(listing.seller_rating_count) ? ` · ★ ${Number(listing.seller_rating).toFixed(1)} · ${Number(listing.seller_rating_count)} ${Number(listing.seller_rating_count) === 1 ? 'review' : 'reviews'}` : ''}</p></div>
                            ${sellerAction}
                          </div>
                          <p style="font-size:11px;color:#777;margin-top:10px;">${actionNote}</p>
                        </div>
                      </div>
                      <div class="detail-description"><h2>Description</h2><p>${esc(listing.description || 'No description provided.')}</p></div>`;
                    await syncStudentActions(detailBox);
                    loadSellerReviews(listing.id);

                    if (moreGrid) {
                        const more = await api('listings/list.php?sort=newest');
                        if (more.success) {
                            moreGrid.innerHTML = more.listings
                                .filter(item => String(item.id) !== String(listing.id))
                                .slice(0, 4)
                                .map(renderCard)
                                .join('');
                                await syncStudentActions(moreGrid);
                        } else {
                            moreGrid.innerHTML = '<p>Could not load more listings.</p>';
                        }
                    }
                } catch {
                    detailBox.innerHTML = '<p>Could not connect. Check your connection and try again.</p>';
                }
            };

            BH.ready.then(u => (!isStudentPage || u) && load());
        }

    /* ---------- DONATED BOOK DETAILS ---------- */
    const donationDetailBox = document.getElementById('donation-details');
    if (donationDetailBox) {
        const donationId = new URLSearchParams(location.search).get('id');
        const breadcrumb = document.getElementById('donation-detail-breadcrumb');
        const loadDonation = async () => {
            if (!donationId) {
                donationDetailBox.innerHTML = '<p>Donation not found. <a href="marketplace.php">Browse the marketplace</a>.</p>';
                return;
            }
            try {
                const response = await api('donations/show.php?id=' + encodeURIComponent(donationId));
                if (!response.success) {
                    donationDetailBox.innerHTML = `<p>${esc(response.message || 'Could not load this donation.')}</p>`;
                    return;
                }
                const donation = response.donation;
                document.title = `${donation.title} | Donated | UIU BookHUB`;
                if (breadcrumb) breadcrumb.textContent = donation.title;
                donationDetailBox.innerHTML = `
                  <div class="detail-grid">
                    <div><div class="gallery-main"><img src="${esc(imgUrl(donation.image_url))}" alt="${esc(donation.title)}"></div></div>
                    <div class="detail-info">
                      <span class="status available">Donated · Free</span>
                      <h1>${esc(donation.title)}</h1>
                      <p class="detail-course">${esc(donation.course_code)} · ${esc(donation.department)}${donation.subject ? ' · ' + esc(donation.subject) : ''}</p>
                      <div class="detail-price">Free</div>
                      <div class="info-list">
                        <div class="info-row"><span>Donor</span><span>${esc(donation.seller_name)}</span></div>
                        <div class="info-row"><span>Condition</span><span>${esc(donation.condition_status)}</span></div>
                        <div class="info-row"><span>Edition</span><span>${esc(donation.edition_author || 'Not specified')}</span></div>
                        <div class="info-row"><span>Type</span><span>${esc(donation.item_type)}</span></div>
                      </div>
                      <p style="font-size:12px;color:#777;margin-top:16px;">Contact the donor directly using the name or Student ID shown above to arrange collection.</p>
                    </div>
                  </div>
                  <div class="detail-description"><h2>Description</h2><p>${esc(donation.description || 'No description provided.')}</p></div>`;
            } catch {
                donationDetailBox.innerHTML = '<p>Could not connect. Check your connection and try again.</p>';
            }
        };
        loadDonation();
    }

    /* ---------- MARKETPLACE (public + student) ---------- */
    const grid = document.getElementById('market-grid');
    if (grid) {
        const root = document.querySelector('.filter-box, .student-filter');
        const searchEl = document.querySelector('.market-search input, .student-market-search input');
        const sortEl = document.querySelector('.sort select, .student-market-sort');
        const countEl = document.getElementById('market-count');
        const typeMap = { 'Textbook': ['Textbook'], 'Notes': ['Lecture Notes', 'Other Notes'], 'Manual': ['Lab Manual'] };
        const sortMap = { 'Price: Low to High': 'price_low', 'Price: High to Low': 'price_high' };
        const isStudentMarketplace = Boolean(document.querySelector('.student-market-layout'));
        const detailsBase = isStudentMarketplace ? '' : BASE;
        const initialSearch = new URLSearchParams(location.search).get('search');
        if (searchEl && initialSearch && !searchEl.value) searchEl.value = initialSearch;

        const load = async () => {
            const p = new URLSearchParams();
            if (searchEl && searchEl.value.trim()) p.set('search', searchEl.value.trim());
            if (sortEl) p.set('sort', sortMap[sortEl.value] || 'newest');
            if (root) {
                const dept = root.querySelector('select'); if (dept) p.set('department', dept.value);
                const course = root.querySelector('input[type=text]'); if (course && course.value.trim()) p.set('course', course.value.trim());
                root.querySelectorAll('input[type=checkbox]:checked').forEach(cb => {
                    const t = cb.parentElement.textContent.trim();
                    if (typeMap[t]) typeMap[t].forEach(v => p.append('type[]', v));
                    else if (['Like New', 'Good', 'Used'].includes(t)) p.append('condition[]', t);
                });
            }
            const [r, donationResult] = await Promise.all([
                api('listings/list.php?' + p.toString()),
                api('donations/list.php?' + p.toString())
            ]);
            if (!r.success || !donationResult.success) return void (grid.innerHTML = '<p>Could not load listings. Please try again.</p>');
            const listings = [
                ...r.listings,
                ...donationResult.donations.map(donation => ({ ...donation, is_donation: true }))
            ];
            const sort = sortEl?.value || 'Newest First';
            if (sort === 'Price: Low to High') listings.sort((a, b) => Number(a.price) - Number(b.price));
            else if (sort === 'Price: High to Low') listings.sort((a, b) => Number(b.price) - Number(a.price));
            else listings.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            if (countEl) countEl.textContent = `${listings.length} ${listings.length === 1 ? 'item' : 'items'} found`;
            grid.innerHTML = listings.length ? listings.map(l => {
                const isDonation = Boolean(l.is_donation);
                const detailUrl = isDonation
                    ? `${isStudentMarketplace ? '../' : BASE}donation-details.php?id=${encodeURIComponent(l.id)}`
                    : `${detailsBase}product-details.php?id=${encodeURIComponent(l.id)}`;
                return `
              <article class="product-card"><div class="product-image"><img src="${esc(imgUrl(l.image_url))}" alt="${esc(l.title)}"><span class="status available">${isDonation ? 'Donated · Free' : esc(l.condition_status)}</span>${isDonation ? '' : `<button class="wish" type="button" data-wishlist-id="${esc(l.id)}" aria-label="Add to wishlist" aria-pressed="false"><i class="fa-regular fa-heart"></i></button>`}</div>
              <div class="product-body"><div class="product-meta"><span class="course">${esc(l.course_code)}</span><span class="dept">${esc(l.department)}</span></div>
              <h3 class="product-title"><a href="${detailUrl}">${esc(l.title)}</a></h3>
              <div class="seller-line">${isDonation ? `<span>Donor: ${esc(l.seller_name || 'Anonymous')}</span>` : `<span class="rating">${Number(l.seller_rating_count) ? `★ ${Number(l.seller_rating).toFixed(1)}` : 'No ratings'}</span><span>by ${esc(l.seller_name)}</span>`}</div>
              <div class="product-bottom"><span class="price">${isDonation ? 'Free' : `৳${Number(l.price).toLocaleString()}`}</span><a href="${detailUrl}" class="btn btn-outline btn-sm">View Details</a></div></div></article>`;
            }).join('')
              : '<p style="grid-column:1/-1;padding:30px;text-align:center;color:#777;">No listings match your search.</p>';
            await syncStudentActions(grid);
        };
        if (root) root.querySelector('button.btn-primary').addEventListener('click', load);
        if (sortEl) sortEl.addEventListener('change', load);
        if (searchEl) { let t; searchEl.addEventListener('input', () => { clearTimeout(t); t = setTimeout(load, 300); }); }
        load();
    }

    /* ---------- WISHLIST ---------- */
    const wishlistGrid = document.getElementById('wishlist-grid');
    if (wishlistGrid) {
        const loadWishlist = async () => {
            let result;
            try {
                result = await api('wishlist/list.php');
            } catch {
                wishlistGrid.innerHTML = '<p>Could not connect. Check your connection and try again.</p>';
                return;
            }
            if (!result.success) {
                wishlistGrid.innerHTML = `<p>${esc(result.message || 'Could not load your wishlist.')}</p>`;
                return;
            }
            wishlistGrid.innerHTML = result.listings.length ? result.listings.map(item => `
              <article class="product-card">
                <div class="product-image">
                  <img src="${esc(imgUrl(item.image_url))}" alt="${esc(item.title)}">
                  <span class="status available">${esc(item.condition_status)}</span>
                  <button class="wish active" type="button" data-wishlist-id="${esc(item.id)}" aria-label="Remove from wishlist" aria-pressed="true"><i class="fa-solid fa-heart"></i></button>
                </div>
                <div class="product-body">
                  <div class="product-meta"><span class="course">${esc(item.course_code)}</span><span class="dept">${esc(item.department)}</span></div>
                  <h3 class="product-title"><a href="product-details.php?id=${encodeURIComponent(item.id)}">${esc(item.title)}</a></h3>
                  <div class="seller-line"><span class="rating">${Number(item.seller_rating_count) ? `★ ${Number(item.seller_rating).toFixed(1)}` : 'No ratings'}</span><span>by ${esc(item.seller_name)}</span></div>
                  <div class="product-bottom"><span class="price">৳${Number(item.price).toLocaleString()}</span><a href="product-details.php?id=${encodeURIComponent(item.id)}" class="btn btn-outline btn-sm">View Details</a></div>
                </div>
              </article>`).join('') : '<p>Your wishlist is empty. Browse the <a href="marketplace.php">marketplace</a> to save an item.</p>';
        };
        BH.ready.then(user => user && user.role === 'student' && loadWishlist());
    }

    /* ---------- PURCHASE REQUESTS ---------- */
    const purchasesBody = document.getElementById('purchases-body');
    if (purchasesBody) {
        const statusClass = { pending: 'pending', accepted: 'available', rejected: 'rejected', cancelled: 'sold' };
        const statusLabel = { pending: 'Pending', accepted: 'Accepted', rejected: 'Declined', cancelled: 'Cancelled' };
        const loadPurchases = async () => {
            let result;
            try {
                result = await api('purchases/mine.php');
            } catch {
                purchasesBody.innerHTML = '<tr><td colspan="5">Could not connect. Check your connection and try again.</td></tr>';
                return;
            }
            if (!result.success) {
                purchasesBody.innerHTML = `<tr><td colspan="5">${esc(result.message || 'Could not load your requests.')}</td></tr>`;
                return;
            }
            purchasesBody.innerHTML = result.requests.length ? result.requests.map(request => `
              <tr>
                <td><div class="book-cell"><img class="book-thumb" src="${esc(imgUrl(request.image_url))}" alt=""><div><strong>${esc(request.title)}</strong><span>${esc(request.course_code)}</span></div></div></td>
                <td>${esc(request.seller_name)}</td>
                <td>৳${Number(request.price).toLocaleString()}</td>
                <td><span class="status ${statusClass[request.status] || 'pending'}">${statusLabel[request.status] || esc(request.status)}</span></td>
                <td>
                  <a href="messages.php?listing_id=${encodeURIComponent(request.listing_id)}" class="btn btn-outline btn-sm">Chat</a>
                  ${request.status === 'accepted' && !request.review_id ? `<button type="button" class="btn btn-primary btn-sm" data-review-request="${esc(request.request_id)}" data-review-title="${esc(request.title)}">Review Seller</button>` : ''}
                  ${request.review_id ? '<span class="status available">Reviewed</span>' : ''}
                </td>
              </tr>`).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;">No buy requests yet. Browse the marketplace to request an item.</td></tr>';
        };
        refreshPurchases = loadPurchases;
        BH.ready.then(user => user && user.role === 'student' && loadPurchases());
    }

    async function loadIncomingRequests() {
        const body = document.getElementById('incoming-requests-body');
        if (!body) return;
        let result;
        try {
            result = await api('purchases/incoming.php');
        } catch {
            body.innerHTML = '<tr><td colspan="5">Could not connect. Check your connection and try again.</td></tr>';
            return;
        }
        if (!result.success) {
            body.innerHTML = `<tr><td colspan="5">${esc(result.message || 'Could not load incoming requests.')}</td></tr>`;
            return;
        }
        body.innerHTML = result.requests.length ? result.requests.map(request => `
          <tr>
            <td><div class="book-cell"><div><strong>${esc(request.title)}</strong><span>${esc(request.course_code)} · ৳${Number(request.price).toLocaleString()}</span></div></div></td>
            <td>${esc(request.buyer_name)}</td>
            <td>${esc(request.buyer_student_id || '—')}</td>
            <td>${new Date(request.created_at).toLocaleDateString()}</td>
            <td><button class="btn btn-primary btn-sm" data-request-action="accept" data-request-id="${esc(request.request_id)}">Accept</button> <button class="btn btn-outline btn-sm" data-request-action="reject" data-request-id="${esc(request.request_id)}">Decline</button></td>
          </tr>`).join('') : '<tr><td colspan="5" style="text-align:center;padding:24px;">No pending buy requests.</td></tr>';
    }
    const incomingRequestsBody = document.getElementById('incoming-requests-body');
    if (incomingRequestsBody) BH.ready.then(user => user && user.role === 'student' && loadIncomingRequests());

    /* ---------- PRIVATE LISTING CHAT ---------- */
    const conversationList = document.getElementById('conversation-list');
    if (conversationList) {
        const chatBody = document.getElementById('chat-body');
        const chatHead = document.getElementById('chat-head');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-message');
        const search = document.getElementById('conversation-search');
        const params = new URLSearchParams(location.search);
        let activeConversationId = Number(params.get('conversation_id')) || 0;
        let conversations = [];

        const renderConversationList = () => {
            const query = (search.value || '').trim().toLowerCase();
            const filtered = conversations.filter(item =>
                `${item.peer_name} ${item.listing_title} ${item.last_message || ''}`.toLowerCase().includes(query)
            );
            conversationList.innerHTML = filtered.length ? filtered.map(item => `
              <button type="button" class="conversation ${Number(item.conversation_id) === activeConversationId ? 'active' : ''}" data-open-conversation="${esc(item.conversation_id)}">
                <span class="avatar">${esc(BH.initials(item.peer_name))}</span>
                <span style="min-width:0;flex:1;text-align:left;"><strong>${esc(item.peer_name)}</strong><p>${esc(item.listing_title)}${item.last_message ? ' · ' + esc(item.last_message) : ' · Start a conversation'}</p><small>${item.last_message_at ? new Date(item.last_message_at).toLocaleString() : ''}</small></span>
              </button>`).join('') : '<p style="padding:18px;color:#777;">No conversations yet. Open a listing and choose Chat with Seller.</p>';
        };

        const loadConversations = async () => {
            try {
                const result = await api('messages/list.php');
                if (!result.success) {
                    conversationList.innerHTML = `<p style="padding:18px;color:#777;">${esc(result.message || 'Could not load conversations.')}</p>`;
                    return;
                }
                conversations = result.conversations;
                renderConversationList();
                if (activeConversationId && !conversations.some(item => Number(item.conversation_id) === activeConversationId)) {
                    activeConversationId = 0;
                }
                if (!activeConversationId && !params.get('listing_id') && conversations.length) {
                    await openConversation(conversations[0].conversation_id);
                } else if (activeConversationId) {
                    await loadThread(false);
                }
            } catch {
                conversationList.innerHTML = '<p style="padding:18px;color:#777;">Could not connect. Try again.</p>';
            }
        };

        const loadThread = async scrollToEnd => {
            if (!activeConversationId) return;
            try {
                const result = await api(`messages/thread.php?conversation_id=${encodeURIComponent(activeConversationId)}`);
                if (!result.success) {
                    chatBody.innerHTML = `<p style="color:#777;">${esc(result.message || 'Could not load this conversation.')}</p>`;
                    return;
                }
                chatHead.innerHTML = `<div class="avatar">${esc(BH.initials(result.conversation.peer_name))}</div><div><strong>${esc(result.conversation.peer_name)}</strong><span>${esc(result.conversation.listing_title)}</span></div>`;
                chatBody.innerHTML = result.messages.length ? result.messages.map(message => `
                  <div class="bubble ${Number(message.sender_id) === Number(result.current_user_id) ? 'out' : 'in'}">
                    ${esc(message.body)}<small>${new Date(message.created_at).toLocaleString()}</small>
                  </div>`).join('') : '<p style="color:#777;">No messages yet. Say hello about this listing.</p>';
                chatForm.hidden = false;
                if (scrollToEnd) chatBody.scrollTop = chatBody.scrollHeight;
            } catch {
                chatBody.innerHTML = '<p style="color:#777;">Could not connect. Try again.</p>';
            }
        };

        const openConversation = async id => {
            activeConversationId = Number(id);
            const url = new URL(location.href);
            url.searchParams.set('conversation_id', String(activeConversationId));
            url.searchParams.delete('listing_id');
            history.replaceState(null, '', url);
            renderConversationList();
            await loadThread(true);
        };

        conversationList.addEventListener('click', e => {
            const item = e.target.closest('[data-open-conversation]');
            if (item) openConversation(item.dataset.openConversation);
        });
        search.addEventListener('input', renderConversationList);
        chatForm.addEventListener('submit', async e => {
            e.preventDefault();
            const body = chatInput.value.trim();
            if (!body || !activeConversationId) return;
            const button = chatForm.querySelector('[type=submit]');
            button.disabled = true;
            try {
                const result = await api('messages/send.php', {
                    conversation_id: activeConversationId,
                    body
                });
                if (!result.success) {
                    showToast(result.message || 'Message could not be sent.');
                    return;
                }
                chatInput.value = '';
                await loadThread(true);
                await loadConversations();
            } catch {
                showToast('Could not connect. Try again.');
            } finally {
                button.disabled = false;
            }
        });

        const openListingConversation = async listingId => {
            try {
                const result = await api('messages/start.php', { listing_id: listingId });
                if (!result.success) {
                    chatBody.innerHTML = `<p style="color:#777;">${esc(result.message || 'Could not start this conversation.')}</p>`;
                    return;
                }
                await loadConversations();
                await openConversation(result.conversation_id);
            } catch {
                chatBody.innerHTML = '<p style="color:#777;">Could not connect. Try again.</p>';
            }
        };

        BH.ready.then(user => {
            if (!user || user.role !== 'student') return;
            const listingId = params.get('listing_id');
            if (listingId) openListingConversation(listingId);
            else loadConversations();
        });
        window.setInterval(() => {
            if (!BH.user || BH.user.role !== 'student') return;
            loadConversations();
            if (activeConversationId) loadThread(false);
        }, 5000);
    }

    /* ---------- ADMIN LISTINGS ---------- */
    const pendingBox = document.getElementById('pending-list');
    if (pendingBox) {
        const cls = { pending: 'pending', approved: 'available', rejected: 'rejected', sold: 'sold' };
        const load = async () => {
            const r = await api('admin/listings.php');
            if (!r.success) return;
            document.getElementById('pending-count').textContent = r.pending_count + ' Pending';
            const pendingListings = r.pending.map(l => `
              <div class="approval-card" style="padding:15px 0;border-top:1px solid #eee;">
                <img class="approval-image" src="${esc(imgUrl(l.image_url))}">
                <div><h3>${esc(l.title)}</h3><p><strong>Seller:</strong> ${esc(l.seller_name)} (${esc(l.student_id)})</p>
                <p><strong>Course:</strong> ${esc(l.course_code)} &nbsp; <strong>Price:</strong> ৳${Number(l.price).toLocaleString()}</p>
                <p><strong>Condition:</strong> ${esc(l.condition_status)}${l.description ? ' — ' + esc(l.description) : ''}</p></div>
                <div class="approval-actions"><button class="btn btn-primary btn-sm" data-kind="listing" data-act="approve" data-id="${l.id}">Approve</button><button class="btn btn-danger btn-sm" data-kind="listing" data-act="reject" data-id="${l.id}">Reject</button></div>
              </div>`).join('');
            const pendingDonations = r.pending_donations.map(donation => `
              <div class="approval-card" style="padding:15px 0;border-top:1px solid #eee;">
                <img class="approval-image" src="${esc(imgUrl(donation.image_url))}" alt="">
                <div><h3>${esc(donation.title)} <span class="status available">Donated · Free</span></h3><p><strong>Donor:</strong> ${esc(donation.seller_name || 'Anonymous')}</p>
                <p><strong>Course:</strong> ${esc(donation.course_code)}</p>
                <p><strong>Condition:</strong> ${esc(donation.condition_status)}${donation.description ? ' — ' + esc(donation.description) : ''}</p></div>
                <div class="approval-actions"><button class="btn btn-primary btn-sm" data-kind="donation" data-act="approve" data-id="${donation.id}">Approve</button><button class="btn btn-danger btn-sm" data-kind="donation" data-act="reject" data-id="${donation.id}">Reject</button></div>
              </div>`).join('');
            pendingBox.innerHTML = pendingListings + pendingDonations || '<p style="padding:20px 0;color:#777;">No pending submissions.</p>';
            document.getElementById('all-listings-body').innerHTML = r.all.map(l => `
              <tr><td>${esc(l.title)}</td><td>${esc(l.seller_name)}</td><td>${esc(l.course_code)}</td>
              <td><span class="status ${cls[l.status]}">${esc(l.status[0].toUpperCase() + l.status.slice(1))}</span></td>
              <td>${l.reject_reason ? esc(l.reject_reason) : ''}</td></tr>`).join('');
            document.getElementById('all-donations-body').innerHTML = r.donations.length ? r.donations.map(donation => `
              <tr><td>${esc(donation.title)}</td><td>${esc(donation.seller_name || 'Anonymous')}</td><td>${esc(donation.course_code)}</td>
              <td><span class="status ${cls[donation.status] || ''}">${esc(donation.status[0].toUpperCase() + donation.status.slice(1))}</span></td>
              <td>${donation.reject_reason ? esc(donation.reject_reason) : ''}</td></tr>`).join('')
                : '<tr><td colspan="5">No donation submissions yet.</td></tr>';
        };
        pendingBox.addEventListener('click', async e => {
            const b = e.target.closest('button[data-act]'); if (!b) return;
            const body = { id: b.dataset.id, action: b.dataset.act, kind: b.dataset.kind || 'listing' };
            if (b.dataset.act === 'reject') { const reason = prompt('Reject reason (seller will see this):'); if (!reason) return; body.reason = reason; }
            const r = await api('admin/review.php', body); showToast(r.message); load();
        });
        BH.ready.then(u => u && load());
    }
});
