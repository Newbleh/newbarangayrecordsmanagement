document.addEventListener('DOMContentLoaded', function () {
    const announcementForm = document.getElementById('announcementForm');
    const announcementList = document.getElementById('announcementList');
    const firebaseStatus = document.getElementById('firebaseStatus');

    if (announcementForm) {
        announcementForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const title = document.getElementById('announcementTitle').value.trim();
            const message = document.getElementById('announcementMessage').value.trim();

            if (!title || !message) {
                setFirebaseStatus('Please provide both title and message.', 'warning');
                return;
            }

            setFirebaseStatus('Saving announcement...', 'info');
            await addAnnouncement({ title, message });
            announcementForm.reset();
        });
    }

    initFirebaseAnnouncements();
});

function initFirebaseAnnouncements() {
    try {
        if (typeof firebase === 'undefined' || typeof firebase.firestore === 'undefined') {
            throw new Error('Firebase SDK is not loaded.');
        }

        if (typeof firebaseConfig === 'undefined') {
            throw new Error('Firebase config is not defined. Update assets/js/firebase-config.js with your project details.');
        }

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        const db = firebase.firestore();
        const announcementsRef = db.collection('announcements').orderBy('createdAt', 'desc');

        announcementsRef.onSnapshot(snapshot => {
            renderAnnouncements(snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() })));
            setFirebaseStatus('Connected to Firebase Cloud backend. Real-time updates enabled.', 'success');
        }, error => {
            console.error('Firestore snapshot error:', error);
            setFirebaseStatus('Unable to load cloud announcements. Check console for details.', 'danger');
        });
    } catch (error) {
        console.error('Firebase initialization error:', error);
        setFirebaseStatus(error.message, 'danger');
    }
}

async function addAnnouncement(announcement) {
    try {
        const db = firebase.firestore();
        await db.collection('announcements').add({
            title: announcement.title,
            message: announcement.message,
            createdAt: firebase.firestore.FieldValue.serverTimestamp()
        });
        setFirebaseStatus('Announcement added successfully.', 'success');
    } catch (error) {
        console.error('Error adding announcement:', error);
        setFirebaseStatus('Failed to save announcement to Firebase.', 'danger');
    }
}

async function deleteAnnouncement(id) {
    try {
        const db = firebase.firestore();
        await db.collection('announcements').doc(id).delete();
        setFirebaseStatus('Announcement deleted.', 'success');
    } catch (error) {
        console.error('Error deleting announcement:', error);
        setFirebaseStatus('Unable to delete announcement.', 'danger');
    }
}

function renderAnnouncements(announcements) {
    const announcementList = document.getElementById('announcementList');
    if (!announcementList) {
        return;
    }

    if (!announcements.length) {
        announcementList.innerHTML = '<p class="text-muted">No cloud announcements found. Add one to get started.</p>';
        return;
    }

    announcementList.innerHTML = announcements.map(item => `
        <div class="card mb-3 announcement-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-2">${escapeHtml(item.title)}</h5>
                        <p class="card-text mb-2">${escapeHtml(item.message)}</p>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAnnouncement('${item.id}')">Delete</button>
                </div>
                <p class="text-muted small mb-0">${formatTimestamp(item.createdAt)}</p>
            </div>
        </div>
    `).join('');
}

function setFirebaseStatus(message, status) {
    const firebaseStatus = document.getElementById('firebaseStatus');
    if (!firebaseStatus) {
        return;
    }
    firebaseStatus.textContent = message;
    firebaseStatus.className = 'alert alert-' + (status || 'info');
}

function escapeHtml(text) {
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function formatTimestamp(timestamp) {
    if (!timestamp) {
        return 'Just now';
    }
    const date = timestamp.toDate ? timestamp.toDate() : new Date(timestamp.seconds * 1000);
    return date.toLocaleString();
}
