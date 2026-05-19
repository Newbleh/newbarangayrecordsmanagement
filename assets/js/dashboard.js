document.addEventListener('DOMContentLoaded', function () {
    // Fetch Dashboard Stats API Button
    const fetchStatsButton = document.getElementById('fetchStatsButton');
    const apiResponseContainer = document.getElementById('apiResponseContainer');

    if (fetchStatsButton) {
        fetchStatsButton.addEventListener('click', async function () {
            fetchStatsButton.disabled = true;
            const originalText = fetchStatsButton.textContent;
            fetchStatsButton.innerHTML = '<span class="loading"></span> Loading...';
            
            try {
                const response = await fetch('../api/dashboard.php?action=get_stats');
                if (!response.ok) {
                    throw new Error('Failed to fetch stats');
                }
                const data = await response.json();

                if (data.success) {
                    displayStatsResponse(data.data, apiResponseContainer);
                } else {
                    showError('Unable to fetch statistics', apiResponseContainer);
                }
            } catch (error) {
                console.error('Error:', error);
                showError('API Error: ' + error.message, apiResponseContainer);
            } finally {
                fetchStatsButton.disabled = false;
                fetchStatsButton.textContent = originalText;
            }
        });
    }

    // Fetch Status Message API Button
    const fetchButton = document.getElementById('apiFetchButton');
    const apiResponse = document.getElementById('apiResponse');
    const apiTitle = document.getElementById('apiTitle');
    const apiDescription = document.getElementById('apiDescription');

    if (fetchButton) {
        fetchButton.addEventListener('click', async function () {
            fetchButton.disabled = true;
            fetchButton.textContent = 'Loading...';
            apiResponse.textContent = 'Fetching latest message...';

            try {
                const response = await fetch('../api/message.php');
                if (!response.ok) {
                    throw new Error('API request failed');
                }
                const data = await response.json();

                apiTitle.textContent = data.title || 'Barangay Service Center';
                apiDescription.textContent = data.description || '';
                apiResponse.textContent = data.responseMessage || 'No data returned from API.';
            } catch (error) {
                apiResponse.textContent = 'Unable to load API message. Please try again later.';
            } finally {
                fetchButton.disabled = false;
                fetchButton.textContent = 'Fetch Status';
            }
        });
    }

    // Delete Blotter Record with Confirmation
    document.querySelectorAll('.delete-blotter-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const recordId = this.dataset.id;
            const recordDesc = this.dataset.description || 'this record';
            
            if (confirm('Are you sure you want to delete ' + recordDesc + '?')) {
                deleteBlotterRecord(recordId, this);
            }
        });
    });
});

// Display stats response dynamically
function displayStatsResponse(stats, container) {
    const html = `
        <div class="fade-in">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Updated System Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card bg-success">
                                <h5>Total Residents</h5>
                                <h2>${stats.total_residents}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-info">
                                <h5>Total Documents</h5>
                                <h2>${stats.total_documents}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning">
                                <h5>Total Blotter Records</h5>
                                <h2>${stats.total_blotter}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-danger">
                                <h5>Pending Blotter Cases</h5>
                                <h2>${stats.pending_blotter}</h2>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">Last updated: ${new Date().toLocaleString()}</p>
                </div>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

// Show error message
function showError(message, container) {
    const html = `
        <div class="alert alert-danger fade-in" role="alert">
            <strong>Error!</strong> ${message}
        </div>
    `;
    container.innerHTML = html;
}

// Delete Blotter Record via API
async function deleteBlotterRecord(id, buttonElement) {
    const row = buttonElement.closest('tr');
    const originalHtml = buttonElement.innerHTML;
    buttonElement.disabled = true;
    buttonElement.innerHTML = '<span class="loading"></span>';

    try {
        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch('../api/dashboard.php?action=delete_blotter', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Delete failed');
        }

        const data = await response.json();
        if (data.success) {
            row.style.opacity = '0';
            setTimeout(() => row.remove(), 300);
        } else {
            alert('Failed to delete record');
            buttonElement.innerHTML = originalHtml;
            buttonElement.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting record: ' + error.message);
        buttonElement.innerHTML = originalHtml;
        buttonElement.disabled = false;
    }
}