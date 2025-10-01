<x-resident-layout>
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.2s;
        }
        .service-card {
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .service-card.selected {
            border: 2px solid #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }
        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        .step-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e2e8f0;
            z-index: 1;
        }
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e2e8f0;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
        }
        .step.active .step-number {
            background-color: #0d6efd;
            color: white;
        }
        .step.completed .step-number {
            background-color: #198754;
            color: white;
        }
        .step-label {
            font-size: 0.85rem;
            color: #64748b;
        }
        .step.active .step-label {
            color: #0d6efd;
            font-weight: 600;
        }
        .document-preview {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 1rem;
            display: none;
        }
        .document-preview.active {
            display: block;
        }
        .document-preview img {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .step-progress {
                flex-direction: column;
                align-items: flex-start;
            }
            .step-progress::before {
                display: none;
            }
            .step {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                text-align: left;
                width: 100%;
            }
            .step-number {
                margin: 0 1rem 0 0;
            }
        }
    </style>

    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold">New Request</h1>
        </div>

        <!-- Step Progress -->
        <div class="step-progress">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <div class="step-label">Select Service</div>
            </div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <div class="step-label">Provide Details</div>
            </div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <div class="step-label">Upload Documents</div>
            </div>
            <div class="step" id="step4">
                <div class="step-number">4</div>
                <div class="step-label">Review & Submit</div>
            </div>
        </div>

        <!-- Step 1: Service Selection -->
        <div class="card border-0 shadow mb-4" id="step1-content">
            <div class="card-body">
                <h5 class="card-title">Select a Service</h5>
                <p class="text-muted mb-4">Choose the type of service or document you need from the barangay.</p>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="clearance">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-file-earmark-text fs-1"></i>
                                </div>
                                <h6>Barangay Clearance</h6>
                                <p class="text-muted small">For employment, business permits, and other requirements</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">Fee: ₱100</span>
                                    <span class="badge bg-secondary">Processing: 1-2 days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="residency">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-house-door fs-1"></i>
                                </div>
                                <h6>Certificate of Residency</h6>
                                <p class="text-muted small">Proof of residency for various applications</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">Fee: ₱50</span>
                                    <span class="badge bg-secondary">Processing: 1 day</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="indigency">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                                <h6>Certificate of Indigency</h6>
                                <p class="text-muted small">For social welfare programs and assistance</p>
                                <div class="mt-3">
                                    <span class="badge bg-success">Free</span>
                                    <span class="badge bg-secondary">Processing: 2-3 days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="business">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-building fs-1"></i>
                                </div>
                                <h6>Business Permit</h6>
                                <p class="text-muted small">Application for new business or renewal</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">Fee: ₱500+</span>
                                    <span class="badge bg-secondary">Processing: 3-5 days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="id">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-person-badge fs-1"></i>
                                </div>
                                <h6>Barangay ID</h6>
                                <p class="text-muted small">Identification card for barangay residents</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">Fee: ₱150</span>
                                    <span class="badge bg-secondary">Processing: 3 days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card service-card card-hover" data-service="other">
                            <div class="card-body text-center">
                                <div class="text-primary mb-3">
                                    <i class="bi bi-question-circle fs-1"></i>
                                </div>
                                <h6>Other Request</h6>
                                <p class="text-muted small">Other barangay services or documents</p>
                                <div class="mt-3">
                                    <span class="badge bg-primary">Fee: Varies</span>
                                    <span class="badge bg-secondary">Processing: Varies</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" id="nextToStep2" disabled>Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 2: Service Details -->
        <div class="card border-0 shadow mb-4" id="step2-content" style="display: none;">
            <div class="card-body">
                <h5 class="card-title">Service Details</h5>
                <p class="text-muted mb-4">Please provide the necessary details for your <span id="selected-service-name">selected service</span>.</p>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="purpose" class="form-label">Purpose of Request <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="purpose" rows="3" placeholder="Please specify the purpose of this document..."></textarea>
                    </div>
                </div>

                <div id="additional-fields">
                    <!-- Dynamic fields will be inserted here based on service selection -->
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" id="backToStep1"><i class="bi bi-arrow-left"></i> Back</button>
                    <button class="btn btn-primary" id="nextToStep3">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 3: Document Upload -->
        <div class="card border-0 shadow mb-4" id="step3-content" style="display: none;">
            <div class="card-body">
                <h5 class="card-title">Upload Required Documents</h5>
                <p class="text-muted mb-4">Please upload the required documents for your <span id="upload-service-name">selected service</span>.</p>

                <div id="required-documents">
                    <!-- Dynamic document requirements will be inserted here -->
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" id="backToStep2"><i class="bi bi-arrow-left"></i> Back</button>
                    <button class="btn btn-primary" id="nextToStep4">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Step 4: Review and Submit -->
        <form id="service-request-form" enctype="multipart/form-data" method="POST" action="{{ route('resident.storeRequestWithDocuments') }}">
            @csrf
            <div class="card border-0 shadow mb-4" id="step4-content" style="display: none;">
                <div class="card-body">
                    <h5 class="card-title">Review and Submit Request</h5>
                    <p class="text-muted mb-4">Please review your request details before submitting.</p>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Request Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row" id="review-summary">
                                <!-- Summary will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms-agreement">
                        <label class="form-check-label" for="terms-agreement">
                            I certify that the information provided is true and correct. I understand that providing false information may result in penalties.
                        </label>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-secondary" id="backToStep3"><i class="bi bi-arrow-left"></i> Back</button>
                        <button class="btn btn-success" id="submit-request" disabled>Submit Request</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h4>Request Submitted Successfully!</h4>
                    <p class="text-muted">Your request has been submitted and is now being processed.</p>
                    <div class="alert alert-info mt-3">
                        <strong>Reference Number:</strong> <span id="reference-number">BRGY-{{ date('Y') }}-{{ str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <p class="small text-muted">You can track the status of your request in the "My Requests" section.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="view-requests">View My Requests</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Current step tracking
            let currentStep = 1;
            let selectedService = null;

            // Service details and requirements
            const serviceDetails = {
                clearance: {
                    name: "Barangay Clearance",
                    fields: `
                        <div class="col-md-6 mb-3">
                            <label for="business-type" class="form-label">Business Type (if applicable)</label>
                            <input type="text" class="form-control" id="business-type" placeholder="e.g., Retail, Food Business">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="clearance-date" class="form-label">Needed By</label>
                            <input type="date" class="form-control" id="clearance-date">
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Proof of Residence", required: true },
                        { name: "Business Permit (if applicable)", required: false }
                    ]
                },
                residency: {
                    name: "Certificate of Residency",
                    fields: `
                        <div class="col-md-6 mb-3">
                            <label for="residency-years" class="form-label">Years of Residency</label>
                            <input type="number" class="form-control" id="residency-years" min="1" placeholder="Number of years">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="residency-date" class="form-label">Needed By</label>
                            <input type="date" class="form-control" id="residency-date">
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Proof of Residence", required: true }
                    ]
                },
                indigency: {
                    name: "Certificate of Indigency",
                    fields: `
                        <div class="col-md-6 mb-3">
                            <label for="family-size" class="form-label">Family Size</label>
                            <input type="number" class="form-control" id="family-size" min="1" placeholder="Number of family members">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="monthly-income" class="form-label">Monthly Income (₱)</label>
                            <input type="number" class="form-control" id="monthly-income" placeholder="Approximate monthly income">
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Proof of Income", required: false },
                        { name: "Proof of Residence", required: true }
                    ]
                },
                business: {
                    name: "Business Permit",
                    fields: `
                        <div class="col-md-6 mb-3">
                            <label for="business-name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business-name" placeholder="Official business name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="business-address" class="form-label">Business Address</label>
                            <input type="text" class="form-control" id="business-address" placeholder="Business location">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="business-type" class="form-label">Business Type</label>
                            <select class="form-select" id="business-type">
                                <option value="">Select business type</option>
                                <option value="retail">Retail</option>
                                <option value="food">Food Business</option>
                                <option value="service">Service Provider</option>
                                <option value="online">Online Business</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Proof of Business Address", required: true },
                        { name: "Previous Permit (for renewal)", required: false },
                        { name: "DTI/SEC Registration", required: true }
                    ]
                },
                id: {
                    name: "Barangay ID",
                    fields: `
                        <div class="col-md-6 mb-3">
                            <label for="id-date" class="form-label">Needed By</label>
                            <input type="date" class="form-control" id="id-date">
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Proof of Residence", required: true },
                        { name: "2x2 ID Picture", required: true }
                    ]
                },
                other: {
                    name: "Other Request",
                    fields: `
                        <div class="col-md-12 mb-3">
                            <label for="request-type" class="form-label">Request Type</label>
                            <input type="text" class="form-control" id="request-type" placeholder="Specify your request type">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="other-details" class="form-label">Additional Details</label>
                            <textarea class="form-control" id="other-details" rows="3" placeholder="Please provide details about your request..."></textarea>
                        </div>
                    `,
                    documents: [
                        { name: "Valid ID", required: true },
                        { name: "Supporting Documents", required: false }
                    ]
                }
            };

            // Service selection
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selected class from all cards
                    serviceCards.forEach(c => c.classList.remove('selected'));

                    // Add selected class to clicked card
                    this.classList.add('selected');

                    // Enable next button
                    document.getElementById('nextToStep2').disabled = false;

                    // Store selected service
                    selectedService = this.dataset.service;
                });
            });

            // Step navigation
            document.getElementById('nextToStep2').addEventListener('click', function() {
                if (!selectedService) return;

                showStep(2);

                // Update service name in step 2
                document.getElementById('selected-service-name').textContent = serviceDetails[selectedService].name;

                // Add service-specific fields
                document.getElementById('additional-fields').innerHTML = serviceDetails[selectedService].fields;
            });

            document.getElementById('backToStep1').addEventListener('click', function() {
                showStep(1);
            });

            document.getElementById('nextToStep3').addEventListener('click', function() {
                // Basic validation
                const purpose = document.getElementById('purpose').value;

                if (!purpose) {
                    alert('Please fill in the purpose field.');
                    return;
                }

                showStep(3);

                // Update service name in step 3
                document.getElementById('upload-service-name').textContent = serviceDetails[selectedService].name;

                // Generate document requirements
                const documentsContainer = document.getElementById('required-documents');
                let documentsHTML = '';

                serviceDetails[selectedService].documents.forEach((doc, index) => {
                    documentsHTML += `
                        <div class="mb-4">
                            <label class="form-label">${doc.name} ${doc.required ? '<span class="text-danger">*</span>' : ''}</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="doc-${index}" ${doc.required ? 'required' : ''}>
                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('doc-${index}').value=''">Clear</button>
                            </div>
                            <div class="form-text">${doc.required ? 'Required document' : 'Optional document'}</div>
                        </div>
                    `;
                });

                documentsContainer.innerHTML = documentsHTML;
            });

            document.getElementById('backToStep2').addEventListener('click', function() {
                showStep(2);
            });

            document.getElementById('nextToStep4').addEventListener('click', function() {
                // Enhanced validation for documents
                let missingDocuments = [];
                
                serviceDetails[selectedService].documents.forEach((doc, index) => {
                    const fileInput = document.getElementById(`doc-${index}`);
                    if (doc.required && (!fileInput || !fileInput.files[0])) {
                        missingDocuments.push(doc.name);
                    }
                });

                if (missingDocuments.length > 0) {
                    alert('Please upload the following required documents:\n• ' + missingDocuments.join('\n• '));
                    return;
                }

                showStep(4);
                generateReviewSummary(); // This should work now
            });

            document.getElementById('backToStep3').addEventListener('click', function() {
                showStep(3);
            });

            // Terms agreement toggle
            document.getElementById('terms-agreement').addEventListener('change', function() {
                document.getElementById('submit-request').disabled = !this.checked;
            });

            // Submit request
            // Submit request
// Submit request - UPDATED VERSION
document.getElementById('submit-request').addEventListener('click', function(event) {
    event.preventDefault();
    
    const submitBtn = this;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

    // Build FormData
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('service_type', selectedService);
    formData.append('purpose', document.getElementById('purpose').value);

    // Append documents
    let hasRequiredDocuments = false;
    serviceDetails[selectedService].documents.forEach((doc, index) => {
        const fileInput = document.getElementById(`doc-${index}`);
        if(fileInput && fileInput.files[0]) {
            hasRequiredDocuments = true;
            formData.append(`documents[${index}][file]`, fileInput.files[0]);
            formData.append(`documents[${index}][document_type]`, doc.name);
        }
    });

    // Check if required documents are uploaded
    if (!hasRequiredDocuments) {
        alert('Please upload at least one required document.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Submit Request';
        return;
    }

    console.log('Submitting to: {{ route("resident.storeRequestWithDocuments") }}');
    
    // Submit via fetch - USING CORRECT ROUTE
    fetch('{{ route("resident.storeRequestWithDocuments") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(async response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server error response:', errorText);
            throw new Error(`Server error: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);
        
        // Show success modal
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        // Reset everything
        resetForm();
    })
    .catch(error => {
        console.error('Submission error:', error);
        alert('Failed to submit request. Please check the console for details.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Submit Request';
    });
});

// Function to reset the form
function resetForm() {
    document.getElementById('service-request-form').reset();
    showStep(1);
    selectedService = null;
    document.querySelectorAll('.service-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.getElementById('nextToStep2').disabled = true;
    document.getElementById('terms-agreement').checked = false;
}

            // View requests button
            document.getElementById('view-requests').addEventListener('click', function() {
                // Redirect to my requests page
                window.location.href = '{{ route("resident.requests") }}';
            });

            // Function to show a specific step
            function showStep(step) {
                // Hide all step contents
                document.querySelectorAll('[id$="content"]').forEach(el => {
                    el.style.display = 'none';
                });

                // Show current step content
                document.getElementById(`step${step}-content`).style.display = 'block';

                // Update step progress
                document.querySelectorAll('.step').forEach((el, index) => {
                    if (index + 1 < step) {
                        el.classList.remove('active');
                        el.classList.add('completed');
                    } else if (index + 1 === step) {
                        el.classList.add('active');
                        el.classList.remove('completed');
                    } else {
                        el.classList.remove('active', 'completed');
                    }
                });

                currentStep = step;
            }

            // Function to generate review summary - FIXED VERSION
function generateReviewSummary() {
    const summaryContainer = document.getElementById('review-summary');
    let summaryHTML = `
        <div class="col-md-6">
            <p><strong>Service Type:</strong> ${serviceDetails[selectedService].name}</p>
            <p><strong>Purpose:</strong> ${document.getElementById('purpose').value}</p>
    `;

    // Add service-specific details
    if (selectedService === 'clearance') {
        const businessType = document.getElementById('business-type')?.value || 'N/A';
        const neededBy = document.getElementById('clearance-date')?.value || 'Not specified';
        summaryHTML += `
            <p><strong>Business Type:</strong> ${businessType}</p>
            <p><strong>Needed By:</strong> ${neededBy}</p>
        `;
    } else if (selectedService === 'residency') {
        const years = document.getElementById('residency-years')?.value || 'Not specified';
        const neededBy = document.getElementById('residency-date')?.value || 'Not specified';
        summaryHTML += `
            <p><strong>Years of Residency:</strong> ${years}</p>
            <p><strong>Needed By:</strong> ${neededBy}</p>
        `;
    } else if (selectedService === 'indigency') {
        const familySize = document.getElementById('family-size')?.value || 'Not specified';
        const monthlyIncome = document.getElementById('monthly-income')?.value || 'Not specified';
        summaryHTML += `
            <p><strong>Family Size:</strong> ${familySize}</p>
            <p><strong>Monthly Income:</strong> ₱${monthlyIncome}</p>
        `;
    } else if (selectedService === 'business') {
        const businessName = document.getElementById('business-name')?.value || 'Not specified';
        const businessType = document.getElementById('business-type')?.options[document.getElementById('business-type')?.selectedIndex]?.text || 'Not specified';
        summaryHTML += `
            <p><strong>Business Name:</strong> ${businessName}</p>
            <p><strong>Business Type:</strong> ${businessType}</p>
        `;
    } else if (selectedService === 'id') {
        const neededBy = document.getElementById('id-date')?.value || 'Not specified';
        summaryHTML += `
            <p><strong>Needed By:</strong> ${neededBy}</p>
        `;
    } else if (selectedService === 'other') {
        const requestType = document.getElementById('request-type')?.value || 'Not specified';
        summaryHTML += `
            <p><strong>Request Type:</strong> ${requestType}</p>
        `;
    }

    summaryHTML += `</div>`;
    
    // Add documents section
    summaryHTML += `
        <div class="col-md-6">
            <p><strong>Submitted On:</strong> ${new Date().toLocaleDateString()}</p>
            <p><strong>Status:</strong> <span class="badge bg-secondary">Pending</span></p>
            <p><strong>Required Documents:</strong></p>
            <ul class="small">
    `;
    
    serviceDetails[selectedService].documents.forEach((doc, index) => {
        const fileInput = document.getElementById(`doc-${index}`);
        const status = fileInput && fileInput.files[0] ? 
            `<span class="text-success">✓ Uploaded</span>` : 
            `<span class="text-danger">✗ Missing</span>`;
        
        summaryHTML += `<li>${doc.name}: ${status}</li>`;
    });
    
    summaryHTML += `
            </ul>
        </div>
    `;
    
    summaryContainer.innerHTML = summaryHTML;
}
        });
    </script>
</x-resident-layout>